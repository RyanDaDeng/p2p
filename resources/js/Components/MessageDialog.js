// Message system using high z-index to appear above modal dialogs
class MessageDialog {
    constructor() {
        this.messages = new Map();
        this.container = null;
        this.isClient = typeof window !== 'undefined' && typeof document !== 'undefined';
        if (this.isClient) {
            this.init();
        }
    }
    
    init() {
        // Only run in browser environment
        if (!this.isClient) return;
        
        // Create a container for messages
        if (!this.container && document.body) {
            this.container = document.createElement('div');
            this.container.id = 'message-dialog-container';
            this.container.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 100000;
                pointer-events: none;
                width: calc(100% - 20px);
                max-width: 500px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
            `;
            document.body.appendChild(this.container);
            
            // Add styles for dark mode support
            if (!document.getElementById('message-dialog-styles')) {
                const style = document.createElement('style');
                style.id = 'message-dialog-styles';
                style.textContent = `
                    .message-dialog-item {
                        pointer-events: auto;
                        margin: 0;
                        padding: 0;
                        border: none;
                        background: transparent;
                        max-width: 500px;
                        width: auto;
                        opacity: 0;
                        transform: translateY(-10px);
                        transition: all 0.3s ease-out;
                    }
                    
                    .message-content {
                        width: 100%;
                        max-width: 420px;
                        padding: 12px 16px;
                        background: rgba(255, 255, 255, 0.95);
                        backdrop-filter: blur(20px);
                        -webkit-backdrop-filter: blur(20px);
                        border: 1px solid rgba(255, 255, 255, 0.5);
                        border-radius: 12px;
                        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
                        font-family: system-ui, -apple-system, sans-serif;
                        font-size: 14px;
                        line-height: 1.5;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        position: relative;
                        z-index: 100001;
                        overflow: hidden;
                    }
                    
                    @media (min-width: 640px) {
                        .message-content {
                            min-width: 320px;
                            padding: 16px 20px;
                            border-radius: 16px;
                            gap: 12px;
                        }
                    }
                    
                    .dark .message-content {
                        background: rgba(17, 24, 39, 0.95);
                        border: 1px solid rgba(75, 85, 99, 0.3);
                    }
                    
                    .gradient-bg {
                        position: absolute;
                        left: 0;
                        top: 0;
                        bottom: 0;
                        width: 60px;
                        opacity: 0.1;
                    }
                    
                    .message-icon {
                        width: 28px;
                        height: 28px;
                        border-radius: 8px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                        position: relative;
                        z-index: 1;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    }
                    
                    @media (min-width: 640px) {
                        .message-icon {
                            width: 32px;
                            height: 32px;
                            border-radius: 10px;
                        }
                    }
                    
                    .message-icon svg {
                        width: 16px;
                        height: 16px;
                    }
                    
                    @media (min-width: 640px) {
                        .message-icon svg {
                            width: 18px;
                            height: 18px;
                        }
                    }
                    
                    .message-text {
                        flex: 1;
                        color: #374151;
                        font-weight: 500;
                        font-size: 13px;
                        position: relative;
                        z-index: 1;
                        word-break: break-word;
                    }
                    
                    @media (min-width: 640px) {
                        .message-text {
                            font-size: 14px;
                        }
                    }
                    
                    .dark .message-text {
                        color: #e5e7eb;
                    }
                    
                    .message-close {
                        background: rgba(229, 231, 235, 0.5);
                        backdrop-filter: blur(10px);
                        -webkit-backdrop-filter: blur(10px);
                        border: none;
                        color: #6b7280;
                        cursor: pointer;
                        width: 28px;
                        height: 28px;
                        border-radius: 8px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transition: all 0.2s;
                        position: relative;
                        z-index: 1;
                    }
                    
                    .dark .message-close {
                        background: rgba(55, 65, 81, 0.5);
                        color: #9ca3af;
                    }
                    
                    .message-close:hover {
                        background: rgba(209, 213, 219, 0.7);
                        transform: scale(1.05);
                    }
                    
                    .dark .message-close:hover {
                        background: rgba(75, 85, 99, 0.7);
                    }
                    
                    .message-close svg {
                        width: 14px;
                        height: 14px;
                    }
                `;
                document.head.appendChild(style);
            }
        }
    }
    
    show(type, message, options = {}) {
        // Only show messages in browser environment
        if (!this.isClient || !this.container) return null;
        
        const id = `msg-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
        
        // Create message element
        const messageEl = document.createElement('div');
        messageEl.id = id;
        messageEl.className = 'message-dialog-item';
        
        const gradients = {
            success: 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
            error: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
            warning: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            info: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'
        };
        
        const icons = {
            success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
            info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        };
        
        messageEl.innerHTML = `
            <div class="message-content" data-type="${type}">
                <!-- Gradient background for icon area -->
                <div class="gradient-bg" style="background: ${gradients[type]};"></div>
                
                <!-- Icon with gradient -->
                <div class="message-icon" style="background: ${gradients[type]};">
                    <svg fill="none" stroke="white" viewBox="0 0 24 24">
                        ${icons[type]}
                    </svg>
                </div>
                
                <!-- Message text -->
                <span class="message-text">${message}</span>
                
                ${options.showClose ? `
                    <button class="message-close" onclick="window.MessageDialog.instance.remove('${id}')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                ` : ''}
            </div>
        `;
        
        // Add to container
        this.container.appendChild(messageEl);
        this.messages.set(id, messageEl);
        
        // Animate in
        requestAnimationFrame(() => {
            messageEl.style.opacity = '1';
            messageEl.style.transform = 'translateY(0)';
        });
        
        // Auto remove
        const duration = options.duration || (type === 'error' ? 7000 : 5000);
        if (duration > 0) {
            setTimeout(() => this.remove(id), duration);
        }
        
        return id;
    }
    
    remove(id) {
        // Only remove in browser environment
        if (!this.isClient) return;
        
        const messageEl = this.messages.get(id);
        if (messageEl) {
            messageEl.style.opacity = '0';
            messageEl.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                messageEl.remove();
                this.messages.delete(id);
            }, 300);
        }
    }
    
    success(message, options = {}) {
        return this.show('success', message, options);
    }
    
    error(message, options = {}) {
        return this.show('error', message, { ...options, showClose: true });
    }
    
    warning(message, options = {}) {
        return this.show('warning', message, options);
    }
    
    info(message, options = {}) {
        return this.show('info', message, options);
    }
}

// Create singleton instance
MessageDialog.instance = new MessageDialog();

// Export for use
export default MessageDialog.instance;

// Only assign to window in browser environment
if (typeof window !== 'undefined') {
    window.MessageDialog = MessageDialog;
}