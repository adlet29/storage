<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Имя')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>
            <div>
                <x-label for="lastname" :value="__('Фамилия')" />

                <x-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required autofocus />
            </div>
            <div>
                <x-label for="patronymic" :value="__('Отчество')" />

                <x-input id="patronymic" class="block mt-1 w-full" type="text" name="patronymic" :value="old('patronymic')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Почта')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Пароль')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Подтверждение пороля')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <!-- Store -->
            <div class="mt-4">
                <label class="col-xs-12" for="store-select">Склад</label>
                <div class="col-sm-9">
                    <select class="form-control" id="store_id" name="store_id" size="1">
                        <option value="0">Пожалуйста выберите склад</option>
                        <option value="1">Склад #1</option>
                        <option value="2">Склад #2</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="col-xs-12">Должность</label>
                <div class="col-xs-12">
                    <div class="radio">
                        <label for="example-radio1">
                            <input type="radio" id="example-radio1" name="position" value="manager"> Руководитель склада
                        </label>
                    </div>
                    <div class="radio">
                        <label for="example-radio2">
                            <input type="radio" id="example-radio2" name="position" value="admin"> Администратор
                        </label>
                    </div>
                </div>
            </div>

            

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
