<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\RecoveryCode;

trait ValidatesTwoFactorAuthentication
{
    /**
     * 验证2FA代码
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @param  string  $code
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateTwoFactorCode($user, $code)
    {
        if (! $user->two_factor_secret) {
            throw ValidationException::withMessages([
                'two_factor_code' => ['用户未启用双重验证。'],
            ]);
        }

        $provider = app(TwoFactorAuthenticationProvider::class);

        // 验证6位数字代码
        if ($code && strlen($code) === 6 && is_numeric($code)) {
            if ($provider->verify(decrypt($user->two_factor_secret), $code)) {
                return true;
            }
        }

        // 验证恢复代码
        if ($code && $this->isValidRecoveryCode($user, $code)) {
            $this->replaceRecoveryCode($user, $code);
            return true;
        }

        throw ValidationException::withMessages([
            'two_factor_code' => ['提供的双重验证码无效。'],
        ]);
    }

    /**
     * 检查是否是有效的恢复代码
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @param  string  $code
     * @return bool
     */
    protected function isValidRecoveryCode($user, $code)
    {
        if (! $user->two_factor_recovery_codes) {
            return false;
        }

        return collect(json_decode(decrypt($user->two_factor_recovery_codes), true))
            ->contains(function ($recoveryCode) use ($code) {
                return hash_equals($recoveryCode, $code);
            });
    }

    /**
     * 替换使用过的恢复代码
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @param  string  $code
     * @return void
     */
    protected function replaceRecoveryCode($user, $code)
    {
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(
                str_replace(
                    $code,
                    RecoveryCode::generate(),
                    decrypt($user->two_factor_recovery_codes)
                )
            ),
        ])->save();
    }

    /**
     * 检查用户是否启用了2FA
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @return bool
     */
    protected function userHasTwoFactorEnabled($user)
    {
        return ! is_null($user->two_factor_secret);
    }
}