/**
 * Image compression service for reducing file sizes before upload
 * Especially useful for mobile photos that can be several MB
 */

class ImageCompressor {
    constructor(options = {}) {
        this.defaultOptions = {
            maxWidth: 1920,
            maxHeight: 1920,
            quality: 0.85,
            mimeType: 'image/jpeg',
            maxSizeMB: 2,
            ...options
        };
    }

    /**
     * Compress an image file
     * @param {File} file - The image file to compress
     * @param {Object} options - Compression options
     * @returns {Promise<File>} - Compressed image file
     */
    async compressImage(file, options = {}) {
        const config = { ...this.defaultOptions, ...options };

        // Skip if not an image
        if (!file.type.startsWith('image/')) {
            return file;
        }

        // Skip if already small enough
        const fileSizeMB = file.size / 1024 / 1024;
        if (fileSizeMB <= config.maxSizeMB && !this.needsResize(file, config)) {
            return file;
        }

        try {
            const bitmap = await this.loadImage(file);
            const canvas = this.resizeImage(bitmap, config);
            const blob = await this.canvasToBlob(canvas, config);

            // If compression made it larger, return original
            if (blob.size >= file.size) {
                return file;
            }

            // Create new File with same name and type
            return new File([blob], file.name, {
                type: blob.type,
                lastModified: Date.now()
            });
        } catch (error) {
            console.error('Image compression failed:', error);
            return file; // Return original on error
        }
    }

    /**
     * Compress multiple images
     * @param {FileList|Array<File>} files - Multiple image files
     * @param {Object} options - Compression options
     * @returns {Promise<Array<File>>} - Array of compressed files
     */
    async compressMultiple(files, options = {}) {
        const fileArray = Array.from(files);
        const promises = fileArray.map(file => this.compressImage(file, options));
        return Promise.all(promises);
    }

    /**
     * Load image and create bitmap
     * @private
     */
    loadImage(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => resolve(img);
                img.onerror = reject;
                img.src = e.target.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    /**
     * Check if image needs resizing
     * @private
     */
    async needsResize(file, config) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    resolve(img.width > config.maxWidth || img.height > config.maxHeight);
                };
                img.onerror = () => resolve(false);
                img.src = e.target.result;
            };
            reader.onerror = () => resolve(false);
            reader.readAsDataURL(file);
        });
    }

    /**
     * Resize image on canvas
     * @private
     */
    resizeImage(img, config) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        // Calculate new dimensions while maintaining aspect ratio
        let { width, height } = this.calculateDimensions(
            img.width,
            img.height,
            config.maxWidth,
            config.maxHeight
        );

        canvas.width = width;
        canvas.height = height;

        // Use better image smoothing
        ctx.imageSmoothingEnabled = true;
        ctx.imageSmoothingQuality = 'high';

        // Draw resized image
        ctx.drawImage(img, 0, 0, width, height);

        return canvas;
    }

    /**
     * Calculate dimensions maintaining aspect ratio
     * @private
     */
    calculateDimensions(origWidth, origHeight, maxWidth, maxHeight) {
        if (origWidth <= maxWidth && origHeight <= maxHeight) {
            return { width: origWidth, height: origHeight };
        }

        const aspectRatio = origWidth / origHeight;
        let width = maxWidth;
        let height = maxWidth / aspectRatio;

        if (height > maxHeight) {
            height = maxHeight;
            width = maxHeight * aspectRatio;
        }

        return {
            width: Math.round(width),
            height: Math.round(height)
        };
    }

    /**
     * Convert canvas to blob with progressive quality reduction
     * @private
     */
    async canvasToBlob(canvas, config) {
        let quality = config.quality;
        let blob = await this.createBlob(canvas, config.mimeType, quality);

        // Progressive quality reduction if still too large
        const targetSize = config.maxSizeMB * 1024 * 1024;
        while (blob.size > targetSize && quality > 0.3) {
            quality -= 0.1;
            blob = await this.createBlob(canvas, config.mimeType, quality);
        }

        return blob;
    }

    /**
     * Create blob from canvas
     * @private
     */
    createBlob(canvas, mimeType, quality) {
        return new Promise((resolve) => {
            canvas.toBlob(
                (blob) => resolve(blob),
                mimeType,
                quality
            );
        });
    }

    /**
     * Get image dimensions without loading full image
     * @param {File} file - Image file
     * @returns {Promise<{width: number, height: number}>}
     */
    async getImageDimensions(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    resolve({
                        width: img.width,
                        height: img.height
                    });
                };
                img.onerror = reject;
                img.src = e.target.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    /**
     * Calculate estimated compressed size
     * @param {File} file - Original file
     * @param {Object} options - Compression options
     * @returns {string} - Formatted size estimate
     */
    estimateCompressedSize(file, options = {}) {
        const config = { ...this.defaultOptions, ...options };
        const originalSizeMB = file.size / 1024 / 1024;

        // Rough estimation based on quality setting
        const compressionRatio = config.quality * 0.7; // Approximate
        const estimatedSizeMB = Math.min(originalSizeMB * compressionRatio, config.maxSizeMB);

        if (estimatedSizeMB < 1) {
            return `~${Math.round(estimatedSizeMB * 1024)} KB`;
        }
        return `~${estimatedSizeMB.toFixed(1)} MB`;
    }

    /**
     * Format file size for display
     * @param {number} bytes - File size in bytes
     * @returns {string} - Formatted size
     */
    formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }
}

// Export singleton instance and class
const imageCompressor = new ImageCompressor();

export default imageCompressor;
export { ImageCompressor };