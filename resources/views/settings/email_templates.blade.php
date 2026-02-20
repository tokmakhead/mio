<x-app-layout>
    <x-page-banner title="E-posta Şablonları"
        subtitle="Otomatik bildirimlerin içeriklerini ve tasarımlarını özelleştirin." />
    <x-settings-tabs />

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($templates as $template)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden flex flex-col">
                        <!-- Header -->
                        <div
                            class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="p-2.5 rounded-xl {{ $template->enabled ? 'bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                    @if($template->type == 'welcome')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($template->type == 'quote')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    @elseif($template->type == 'invoice')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    @elseif($template->type == 'service_expiry')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">
                                        @if($template->type == 'welcome') Hoş Geldiniz Mesajı
                                        @elseif($template->type == 'quote') Teklif Gönderimi
                                        @elseif($template->type == 'invoice') Fatura Gönderimi
                                        @elseif($template->type == 'service_expiry') Hizmet Süresi Uyarısı
                                        @else {{ ucfirst($template->type) }}
                                        @endif
                                    </h3>
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ $template->type }}
                                        SYSTEM</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="flex h-2 w-2 rounded-full {{ $template->enabled ? 'bg-success-500' : 'bg-gray-400' }} mr-2"></span>
                                <span
                                    class="text-xs font-semibold {{ $template->enabled ? 'text-success-600 dark:text-success-400' : 'text-gray-500' }}">
                                    {{ $template->enabled ? 'Aktif' : 'Pasif' }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex-grow">
                            <form action="{{ route('settings.templates.update', $template) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="subject_{{$template->id}}" value="Konu"
                                            class="text-xs uppercase" />
                                        <x-text-input id="subject_{{$template->id}}" name="subject" type="text"
                                            class="block w-full mt-1 bg-gray-50/50" :value="$template->subject" />
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <x-input-label for="html_body_{{$template->id}}" value="İçerik (Görsel Editör)"
                                                class="text-xs uppercase" />
                                            <div class="group relative">
                                                <button type="button"
                                                    class="text-[10px] text-primary-600 font-bold hover:underline">
                                                    DEĞİŞKENLER
                                                </button>
                                                <div
                                                    class="hidden group-hover:block absolute right-0 bottom-full mb-2 w-64 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl p-3 z-50">
                                                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">
                                                        Kullanılabilir Değişkenler</p>
                                                    <ul
                                                        class="text-[11px] space-y-1 font-mono text-gray-600 dark:text-gray-400">
                                                        <li>@{{customer_name}}</li>
                                                        <li>@{{invoice_number}}</li>
                                                        <li>@{{quote_number}}</li>
                                                        <li>@{{service_name}}</li>
                                                        <li>@{{expiry_date}}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <textarea id="html_body_{{$template->id}}" name="html_body" rows="10"
                                            class="email-editor block w-full text-sm bg-gray-50/50 border-gray-300 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-300 rounded-lg">{{ $template->html_body }}</textarea>
                                    </div>
                                    <div class="flex items-center justify-between pt-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="enabled" value="1" {{ $template->enabled ? 'checked' : '' }} class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600">
                                            </div>
                                            <span
                                                class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Etkinleştir</span>
                                        </label>
                                        <button type="submit"
                                            class="px-4 py-2 bg-gray-900 dark:bg-white dark:text-gray-900 text-white text-xs font-bold rounded-lg hover:opacity-90 transition-opacity">
                                            KAYDET
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Actions Footer -->
                        <div
                            class="px-6 py-4 bg-gray-50/30 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-700 grid grid-cols-2 gap-4">
                            <button type="button" onclick="previewTemplate({{ $template->id }})"
                                class="flex items-center justify-center py-2 text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                ÖNİZLE
                            </button>
                            <button type="button" onclick="testTemplate({{ $template->id }})"
                                class="flex items-center justify-center py-2 text-xs font-bold text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/10 rounded-lg border border-primary-200 dark:border-primary-800/50 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                TEST ET
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modals for Preview and Test (Simplified logic) -->
    <div id="previewModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closePreview()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div
                    class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">E-posta Önizleme</h3>
                    <button onclick="closePreview()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="bg-white p-6" id="previewContainer">
                    <!-- Preview content will be loaded here -->
                    <iframe id="previewFrame" class="w-full h-[600px] border-none"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Test Email -->
    <div id="testModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeTest()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div
                    class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Test E-postası Gönder</h3>
                    <button onclick="closeTest()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 text-left">
                    <input type="hidden" id="test_template_id">
                    <div class="relative">
                        <x-input-label for="test_recipient" value="Alıcı E-posta Adresi" class="text-xs uppercase" />
                        <x-text-input id="test_recipient" type="email" class="block w-full mt-1 bg-gray-50/50"
                            placeholder="ornek@email.com"
                            oninput="document.getElementById('email_error').classList.add('hidden')" />

                        <!-- Premium Validation Tooltip -->
                        <div id="email_error" class="hidden absolute z-50 mt-1 left-2">
                            <div
                                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl p-2.5 flex items-center space-x-2.5 whitespace-nowrap animate-in fade-in slide-in-from-top-1 duration-200">
                                <div class="bg-orange-500 p-1 rounded-md text-white">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1-1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Lütfen bu alanı
                                    doldurun.</span>
                            </div>
                            <!-- Tail -->
                            <div
                                class="absolute -top-1 left-4 w-2 h-2 bg-white dark:bg-gray-800 border-t border-l border-gray-200 dark:border-gray-700 transform rotate-45">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeTest()"
                        class="px-4 py-2 text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                        İPTAL
                    </button>
                    <button type="button" onclick="sendTestEmail()"
                        class="px-6 py-2 bg-primary-600 text-white text-xs font-bold rounded-lg hover:bg-primary-700 transition-colors">
                        GÖNDER
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.5/jodit.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.5/jodit.min.js"></script>
        <script>
            const brandConfig = {
                name: "{{ $brandSettings['site_title'] ?? ($settings->site_name ?? 'MIONEX') }}",
                logo: "{{ $brandSettings['logo_path'] ?? ($settings->logo_path ?? '') }}",
                color: "{{ $brandSettings['primary_color'] ?? '#dc2626' }}",
                app_name: "{{ $settings->site_name ?? 'MIONEX' }}"
            };

            const joditEditors = {};
            document.querySelectorAll('.email-editor').forEach(function (textarea) {
                joditEditors[textarea.id] = Jodit.make('#' + textarea.id, {
                    height: 400,
                    language: 'tr',
                    toolbarButtonSize: 'small',
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'default',
                    buttons: [
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'font', 'fontsize', 'paragraph', '|',
                        'align', '|',
                        'ul', 'ol', 'indent', 'outdent', '|',
                        'link', 'image', 'table', '|',
                        'undo', 'redo', '|',
                        'source'
                    ],
                    style: { font: '14px Outfit, sans-serif' },
                });
            });

            function previewTemplate(id) {
                const frame = document.getElementById('previewFrame');
                const editorKey = 'html_body_' + id;
                const content = joditEditors[editorKey] ? joditEditors[editorKey].value : document.getElementById(editorKey).value;
                const subject = document.getElementById('subject_' + id).value;

                // Variable replacement for preview
                let previewContent = content;
                const replacements = {
                    '{{customer_name}}': 'Ahmet Yılmaz',
                    '{{invoice_number}}': 'INV-2026-00123',
                    '{{quote_number}}': 'TEKLIF-98765',
                    '{{service_name}}': 'MIONEX Premium Paket',
                    '{{expiry_date}}': '27.02.2026',
                    '{{grand_total}}': '4,500.00 TRY',
                    '{{brand_name}}': brandConfig.name
                };

                Object.keys(replacements).forEach(key => {
                    previewContent = previewContent.replace(new RegExp(key, 'g'), replacements[key]);
                });

                // Professional email wrapper for preview
                const styledContent = `<html>
                                                <head>
                                                    <style>
                                                        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f9; padding: 40px 20px; margin: 0; }
                                                        .email-container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #eef2f5; }
                                                        .email-header { background: ${brandConfig.color}; padding: 30px; text-align: center; color: white; }
                                                        .email-body { padding: 40px; line-height: 1.6; color: #334455; font-size: 15px; }
                                                        .email-footer { background: #f8fafc; padding: 20px; text-align: center; color: #94a3b8; font-size: 12px; border-top: 1px solid #edf2f7; }
                                                        .email-logo-img { max-height: 50px; width: auto; filter: brightness(0) invert(1); }
                                                        .email-logo-text { font-size: 24px; font-weight: 800; letter-spacing: -1px; }
                                                        h1, h2, h3 { color: #1e293b; margin-top: 0; }
                                                        p { margin-bottom: 20px; }
                                                        a { color: ${brandConfig.color}; text-decoration: underline; }
                                                    </style>
                                                </head>
                                                <body>
                                                    <div class="email-container">
                                                        <div class="email-header">
                                                            ${brandConfig.logo ? `<img src="${brandConfig.logo}" class="email-logo-img">` : `<div class="email-logo-text">${brandConfig.name}</div>`}
                                                        </div>
                                                        <div class="email-body">${previewContent}</div>
                                                        <div class="email-footer">
                                                            Bu bir sistem bildirimidir. Lütfen bu e-postayı yanıtlamayın.<br>
                                                            &copy; {{ date('Y') }} ${brandConfig.name}. Tüm hakları saklıdır.
                                                        </div>
                                                    </div>
                                                </body>
                                            </html>`;

                const doc = frame.contentWindow.document;
                doc.open();
                doc.write(styledContent);
                doc.close();

                document.getElementById('previewModal').classList.remove('hidden');
            }

            function closePreview() {
                document.getElementById('previewModal').classList.add('hidden');
            }

            function testTemplate(id) {
                document.getElementById('test_template_id').value = id;
                document.getElementById('test_recipient').value = '';
                document.getElementById('email_error').classList.add('hidden');
                document.getElementById('testModal').classList.remove('hidden');
                setTimeout(() => document.getElementById('test_recipient').focus(), 100);
            }

            function closeTest() {
                document.getElementById('testModal').classList.add('hidden');
            }

            function sendTestEmail() {
                const id = document.getElementById('test_template_id').value;
                const recipient = document.getElementById('test_recipient').value;
                const emailError = document.getElementById('email_error');

                if (recipient && recipient.indexOf('@') !== -1 && recipient.indexOf('.') !== -1) {
                    emailError.classList.add('hidden');
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('settings.templates.test', ':id') }}".replace(':id', id);

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";
                    form.appendChild(csrf);

                    const to = document.createElement('input');
                    to.type = 'hidden';
                    to.name = 'to';
                    to.value = recipient;
                    form.appendChild(to);

                    document.body.appendChild(form);
                    form.submit();
                } else {
                    emailError.classList.remove('hidden');
                }
            }
        </script>
    @endpush
</x-app-layout>