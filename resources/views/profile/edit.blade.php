<x-app-layout>
    <x-page-banner title="Profil Ayarları"
        subtitle="Kişisel bilgilerinizi ve hesap ayarlarınızı buradan güncelleyebilirsiniz." />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <x-card>
                    @include('profile.partials.update-profile-information-form')
                </x-card>

                <x-card>
                    @include('profile.partials.update-password-form')
                </x-card>
            </div>

            <x-card class="border-rose-100 dark:border-rose-900/30">
                @include('profile.partials.delete-user-form')
            </x-card>
        </div>
    </div>
</x-app-layout>