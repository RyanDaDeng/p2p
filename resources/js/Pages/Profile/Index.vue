<template>
    <P2PAppLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-slate-950">
            <!-- 用户信息头部 -->
            <div class="bg-gradient-to-b from-emerald-600 to-emerald-700 dark:from-emerald-700 dark:to-emerald-800 px-4 sm:px-6 lg:px-8 pt-8 pb-20">
                <div class="max-w-7xl mx-auto">
                    <!-- 用户头像和基本信息 -->
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-4 md:gap-6">
                        <Link href="/user/profile#tab-profile" class="relative group cursor-pointer">
                            <!-- Use profile photo if available, otherwise show initial -->
                            <img
                                v-if="$page.props.auth.user?.profile_photo_url"
                                :src="$page.props.auth.user.profile_photo_url"
                                :alt="$page.props.auth.user?.name"
                                class="h-20 w-20 md:h-28 md:w-28 rounded-full object-cover border-4 border-white/30 group-hover:border-white/50 transition-all"
                            />
                            <div v-else class="h-20 w-20 md:h-28 md:w-28 rounded-full bg-white/20 backdrop-blur flex items-center justify-center group-hover:bg-white/30 transition-all">
                                <span class="text-3xl md:text-4xl font-bold text-white">
                                    {{ $page.props.auth.user?.name?.[0]?.toUpperCase() || 'U' }}
                                </span>
                            </div>
                            <!-- Camera/Edit Icon -->
                            <div class="absolute bottom-1 right-1 bg-white dark:bg-slate-800 rounded-full p-1.5 shadow-lg group-hover:scale-110 transition-transform flex items-center justify-center">
                                <svg class="w-3 h-3 md:w-3.5 md:h-3.5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div v-if="$page.props.auth.user?.is_verified"
                                 class="absolute -top-1 -right-1 bg-amber-500 rounded-full p-1 md:p-1.5">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </Link>
                        <div class="flex-1 text-center md:text-left">
                            <h2 class="text-xl font-bold text-white">{{ $page.props.auth.user?.name || '用户' }}</h2>
                            <p class="text-emerald-100 text-sm mt-1">{{ $page.props.auth.user?.email }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-white text-sm font-medium">{{ $page.props.auth.user?.avg_rating ? Number($page.props.auth.user.avg_rating).toFixed(1) : '0.0' }}</span>
                                </div>
                                <span class="text-emerald-100 text-sm">成交 {{ $page.props.auth.user?.trades_count || 0 }} 笔</span>
                            </div>
                            <!-- 验证状态信息 -->
                            <div class="flex flex-wrap items-center gap-2 mt-3 text-xs text-emerald-100">
                                <!-- 邮箱验证状态 -->
                                <span v-if="$page.props.auth.user?.email_verified_at" class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    邮箱已验证
                                </span>
                                <span v-else class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    邮箱未验证
                                </span>
                                
                                <span class="opacity-60">·</span>
                                
                                <!-- 双重验证状态 -->
                                <span v-if="$page.props.auth.user?.two_factor_confirmed_at" class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    双重验证已开启
                                </span>
                                <span v-else class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    双重验证未开启
                                </span>
                                
                                <span class="opacity-60">·</span>
                                
                                <!-- 加入日期 -->
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    加入于 {{ $page.props.auth.user?.created_at ? new Date($page.props.auth.user.created_at).toLocaleDateString('zh-CN') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 统计卡片 -->
            <div class="px-4 sm:px-6 lg:px-8 -mt-12">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white dark:bg-slate-900 rounded-xl p-4 md:p-6 shadow-lg">
                        <div class="grid grid-cols-3 md:grid-cols-5 gap-4 md:gap-6">
                            <div class="text-center">
                                <p class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-slate-100">
                                    {{ $page.props.auth.user?.trades_count || 0 }}
                                </p>
                                <p class="text-xs md:text-sm text-gray-500 dark:text-slate-400 mt-1">总交易</p>
                            </div>
                            <div class="text-center border-x border-gray-200 dark:border-slate-800 md:border-x-0 md:border-l">
                                <p class="text-2xl md:text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ $page.props.auth.user?.total_reviews || 0 }}
                                </p>
                                <p class="text-xs md:text-sm text-gray-500 dark:text-slate-400 mt-1">评价数</p>
                            </div>
                            <div class="text-center md:border-l border-gray-200 dark:border-slate-800">
                                <p class="text-2xl md:text-3xl font-bold text-amber-600 dark:text-amber-400">
                                    {{ $page.props.auth.user?.avg_rating ? Number($page.props.auth.user.avg_rating).toFixed(1) : '0.0' }}
                                </p>
                                <p class="text-xs md:text-sm text-gray-500 dark:text-slate-400 mt-1">评分</p>
                            </div>
                            <!-- Desktop only stats -->
                            <div class="hidden md:block text-center border-l border-gray-200 dark:border-slate-800">
                                <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ props.user?.active_ads || 0 }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">活跃广告</p>
                            </div>
                            <div class="hidden md:block text-center border-l border-gray-200 dark:border-slate-800">
                                <p class="text-3xl font-bold text-gray-900 dark:text-slate-100">
                                    {{ props.user?.response_time ? '< ' + props.user.response_time + '分钟' : '暂无' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">响应时间</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 菜单项列表 -->
            <div class="px-4 sm:px-6 lg:px-8 mt-6">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        <!-- 账户管理 -->
                        <div class="bg-white dark:bg-slate-900 rounded-xl overflow-hidden">
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 px-4 pt-4 pb-2">账户管理</h3>
                        
                        <Link href="/user/profile#tab-profile" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">个人信息</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">管理您的名称和邮箱</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>

                        <Link href="/user/profile#tab-password" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">修改密码</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">更新您的登录密码</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>

                        <Link href="/user/profile#tab-2fa" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">双重验证</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">保护您的账户安全</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>

                        <Link href="/user/profile#tab-devices" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">登录设备</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">管理登录设备</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>

                    <!-- 交易设置 -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl overflow-hidden">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 px-4 pt-4 pb-2">交易设置</h3>

                        <Link href="/orders" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">订单管理</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">查看和管理您的订单</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>

                        <Link href="/publish" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">发布广告</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">管理您的交易广告</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>

                        <Link href="/wallet/address-verification" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">地址管理</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">管理收币地址</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>

                        <Link href="/user/profile#tab-kyc" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">实名认证</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">完成身份验证</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>

                    <!-- 管理员功能 -->
                    <div v-if="props.isAdmin" class="bg-white dark:bg-slate-900 rounded-xl overflow-hidden">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 px-4 pt-4 pb-2">管理功能</h3>
                        
                        <Link href="/arbitration" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">仲裁管理</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">处理争议订单</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>

                    <!-- 通知管理 -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl overflow-hidden">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 px-4 pt-4 pb-2">通知管理</h3>

                        <Link href="/notifications/settings" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">Telegram设置</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">管理 Telegram 通知</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>

                    <!-- 其他 -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl overflow-hidden">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-slate-400 px-4 pt-4 pb-2">其他</h3>

                        <Link :href="`/trader/profile/${$page.props.auth.user?.id}`" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">资料名片</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">查看您的公开资料</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>

                        <Link :href="route('logout')" method="post" as="button" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors w-full">
                            <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <p class="text-sm font-medium text-gray-900 dark:text-slate-100">退出登录</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">安全退出账户</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                    </div>
                </div>
            </div>

            <!-- 底部间距 -->
            <div class="h-20"></div>
        </div>
    </P2PAppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import P2PAppLayout from '@/Layouts/P2PAppLayout.vue';

const $page = usePage();

// Props
const props = defineProps({
    user: Object,
    isAdmin: Boolean
});

// Use props.user instead of $page.props.auth.user
const user = computed(() => props.user || {});

// 当前激活的 tab
const activeTab = ref('');

// 处理 hash 变化
const handleHashChange = () => {
    const hash = window.location.hash.slice(1);
    if (hash && ['email', 'password', '2fa', 'kyc', 'devices'].includes(hash)) {
        activeTab.value = hash;
    } else {
        activeTab.value = '';
    }
};

// 监听 hash 变化
onMounted(() => {
    handleHashChange();
    window.addEventListener('hashchange', handleHashChange);
});

</script>