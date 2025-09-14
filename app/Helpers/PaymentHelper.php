<?php

namespace App\Helpers;

class PaymentHelper
{
    /**
     * Get all payment methods as key-value pairs
     *
     * @return array ['payment_value' => 'Payment Label', ...]
     */
    public static function getKeyValuePairs(): array
    {
        $keyValue = [];
        $categories = config('payment_methods.categories', []);

        foreach ($categories as $category) {
            foreach ($category['methods'] as $method) {
                $keyValue[$method['value']] = $method['label'];
            }
        }

        return $keyValue;
    }

    /**
     * Get payment method label by value
     *
     * @param string $value
     * @return string|null
     */
    public static function getLabel(string $value): ?string
    {
        $keyValue = self::getKeyValuePairs();
        return $keyValue[$value] ?? null;
    }

    /**
     * Get all payment categories
     *
     * @return array
     */
    public static function getCategories(): array
    {
        return config('payment_methods.categories', []);
    }

    /**
     * Get payment method details by value
     *
     * @param string $value
     * @return array|null
     */
    public static function getMethodDetails(string $value): ?array
    {
        $categories = config('payment_methods.categories', []);

        foreach ($categories as $category) {
            foreach ($category['methods'] as $method) {
                if ($method['value'] === $value) {
                    return array_merge($method, [
                        'category' => $category['name'],
                        'category_id' => $category['id'],
                        'category_icon' => $category['icon'],
                        'category_color' => $category['color']
                    ]);
                }
            }
        }

        return null;
    }

    /**
     * Get popular payment methods
     *
     * @return array
     */
    public static function getPopularMethods(): array
    {
        $popular = [];
        $categories = config('payment_methods.categories', []);

        foreach ($categories as $category) {
            foreach ($category['methods'] as $method) {
                if ($method['popular'] ?? false) {
                    $popular[] = array_merge($method, [
                        'category' => $category['name'],
                        'category_id' => $category['id'],
                        'category_icon' => $category['icon'],
                        'category_color' => $category['color']
                    ]);
                }
            }
        }

        return $popular;
    }

    /**
     * Check if payment method exists
     *
     * @param string $value
     * @return bool
     */
    public static function exists(string $value): bool
    {
        return self::getLabel($value) !== null;
    }
}