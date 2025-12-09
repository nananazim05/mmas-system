<x-app-layout>
    <x-slot name="header">
        {{ __('messages.my_profile') }}
    </x-slot>

    <div class="space-y-6">
        
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border-t-4 border-[#B6192E]">
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">
                    {{ Auth::user()->role === 'admin' ? __('messages.account_info') : __('messages.personal_details') }}
                </h3>

                @if($user->role === 'admin')
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-4 text-blue-800 text-sm">
                            <strong>{{ __('messages.admin_account_info') }}</strong>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-500 text-sm font-bold mb-1">{{ __('messages.official_email') }}</label>
                                <div class="text-lg font-bold text-gray-800 bg-gray-50 p-3 rounded border border-gray-200">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-500 text-sm font-bold mb-1">{{ __('messages.system_role') }}</label>
                                <div class="text-lg font-bold text-gray-800 bg-gray-50 p-3 rounded border border-gray-200 capitalize">
                                    {{ $user->role }}
                                </div>
                            </div>
                        </div>
                    </div>

                @else

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="mb-4">
                            <label class="block text-gray-500 text-sm font-bold mb-1">{{ __('messages.full_name') }}</label>
                            <div class="text-lg font-bold text-gray-800 bg-gray-50 p-3 rounded border border-gray-200">
                                {{ $user->name }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-500 text-sm font-bold mb-1">{{ __('messages.staff_no') }}</label>
                            <div class="text-lg font-bold text-gray-800 bg-gray-50 p-3 rounded border border-gray-200">
                                {{ $user->staff_number ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-500 text-sm font-bold mb-1">{{ __('messages.ic_no') }}</label>
                            <div class="text-lg font-bold text-gray-800 bg-gray-50 p-3 rounded border border-gray-200">
                                {{ $user->ic_number ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-500 text-sm font-bold mb-1">{{ __('messages.official_email') }}</label>
                            <div class="text-lg font-bold text-gray-800 bg-gray-50 p-3 rounded border border-gray-200">
                                {{ $user->email }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-500 text-sm font-bold mb-1">{{ __('messages.section_label') }}</label>
                            <div class="text-lg font-bold text-gray-800 bg-gray-50 p-3 rounded border border-gray-200">
                                {{ $user->section ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-500 text-sm font-bold mb-1">{{ __('messages.division_label') }}</label>
                            <div class="text-lg font-bold text-gray-800 bg-gray-50 p-3 rounded border border-gray-200">
                                {{ $user->division ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-500 text-sm font-bold mb-1">{{ __('messages.grade_label') }}</label>
                            <div class="text-lg font-bold text-gray-800 bg-gray-50 p-3 rounded border border-gray-200">
                                {{ $user->grade ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-500 text-sm font-bold mb-1">{{ __('messages.system_role') }}</label>
                            <div class="text-lg font-bold text-gray-800 bg-gray-50 p-3 rounded border border-gray-200 capitalize">
                                {{ $user->role }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-yellow-50 text-yellow-800 text-sm rounded border border-yellow-200 flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p>{{ __('messages.profile_locked_note') }}</p>
                    </div>

                @endif
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden border-t-4 border-gray-600">
            <div class="p-8">
                <header>
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ __('messages.update_password') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('messages.password_desc') }}
                    </p>
                </header>

                <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="block font-medium text-sm text-gray-700">{{ __('messages.current_password') }}</label>
                        <input id="current_password" name="current_password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#B6192E] focus:ring-[#B6192E]" autocomplete="current-password" />
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password" class="block font-medium text-sm text-gray-700">{{ __('messages.new_password') }}</label>
                        <input id="password" name="password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#B6192E] focus:ring-[#B6192E]" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('messages.confirm_password') }}</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#B6192E] focus:ring-[#B6192E]" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('messages.save_password') }}
                        </button>

                        @if (session('status') === 'password-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-green-600 font-bold"
                            >{{ __('messages.password_saved') }}</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>