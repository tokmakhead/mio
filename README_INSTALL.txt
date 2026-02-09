# MIONEX Admin Panel v1.0 - Kurulum Kılavuzu

MIONEX'i tercih ettiğiniz için teşekkür ederiz. Bu belge, scriptinizi sunucunuza sorunsuz bir şekilde kurmanıza yardımcı olacaktır.

## Gereksinimler
- PHP 8.2 veya üzeri
- MySQL 8.0 / MariaDB 10.4 veya üzeri
- Apache / Nginx (mod_rewrite aktif)
- PHP Eklentileri: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD

## Kurulum Adımları

1. **Dosyaları Yükleyin:** ZIP içeriğini web sunucunuzun ana dizinine (public_html vb.) yükleyin.
2. **Veritabanı Oluşturun:** Hosting panelinizden yeni bir MySQL veritabanı ve kullanıcısı oluşturun.
3. **Sihirbazı Başlatın:** Tarayıcınızdan alan adınıza gidin (Örn: `https://siteniz.com`). Sistem sizi otomatik olarak kurulum sihirbazına (`/install`) yönlendirecektir.
4. **Lisans Doğrulama:** Satın alma kodunuzu ve bilgilerinizi girerek lisansınızı aktif edin.
5. **Veritabanı Bilgileri:** Oluşturduğunuz veritabanı bilgilerini girin.
6. **Yönetici Hesabı:** İlk yönetici (admin) hesabınızı oluşturun.
7. **Bitti:** Kurulum tamamlandıktan sonra `/install` klasörü otomatik olarak devre dışı kalacaktır.

## Güvenlik Öneri
- `.env` dosyanızın yetkilerini (chmod) `640` veya `600` olarak ayarlayın.
- Veritabanı şifrenizde karmaşık karakterler kullanın.

## Destek
Herhangi bir sorun yaşarsanız müşteri panelimiz üzerinden destek talebi oluşturabilirsiniz.

---
© 2026 MIONEX - Tüm hakları saklıdır.
