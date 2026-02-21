# MIONEX Master Panel: Canlı Test Rehberi 🚀

Bu doküman, Master Panel üzerinde geliştirilen tüm özelliklerin doğrulanması için hazırlanmış senaryoları içerir.

## 🔑 Senaryo 1: Güvenlik & İzolasyon (Phase 1 & 6)
**Amaç:** Sistemin yetkisiz erişime kapalı ve rollere duyarlı olduğunu doğrulamak.

1.  **İzolasyon Testi:** Tarayıcıda gizli sekme açın ve `/master` adresine gidin. Giriş yapmadan erişemediğinizi doğrulayın.
2.  **Rol Testi:** `/master/admins/create` sayfasından bir **Support** hesabı oluşturun. 
    - Support hesabı ile giriş yapın. Lisans ekleyip silebildiğinizi ama yeni yönetici oluşturamadığınızı doğrulayın.
3.  **Audit Logs:** `/master/logs` sayfasına gidin. Yaptığınız giriş ve admin oluşturma eylemlerinin IP adresiyle beraber buraya düştüğünü görün.

## 🎫 Senaryo 2: Akıllı Lisans Yönetimi (Phase 2 & 7)
**Amaç:** Lisans kısıtlamalarının ve özellik bayraklarının (Features) çalıştığını görmek.

1.  **Sınırlı Lisans:** Bir lisans oluşturun ve "Aktivasyon Limiti"ni `1` yapın. 
    - İstemci tarafından bu lisansı iki farklı domainde kullanmayı deneyin (API tarafı `activation_limit_exceeded` dönmelidir).
2.  **Özellik Denetimi (Feature Flags):** Lisans oluştururken `Features` alanına `{"reporting": true, "analytics": false}` gibi bir JSON girin.
    - API çıktısında `features` alanının bu JSON'u döndüğünü doğrulayın.
3.  **Deneme Sürümü:** Bir lisans oluşturun ve `Trial` seçeneğini aktif edip süreyi yarına kurun. Yarın olduğunda lisansın otomatik `expired` durumuna düştüğünü gözlemleyin.

## 🛡️ Senaryo 3: API İmzası & Güvenlik (Phase 1 & 10)
**Amaç:** HMAC imzasının ve Rate Limiting'in korumasını test etmek.

1.  **Imza Testi:** Postman üzerinden `/api/master/verify-license` uç noktasına imzasız (veya yanlış `X-Mio-Signature` ile) istek atın. Sistem `Unauthorized / Invalid Signature` dönmelidir.
2.  **Rate Limiting:** Aynı IP üzerinden API'ya 1 dakika içinde 60'tan fazla istek gönderin. Sistem `429 Too Many Requests` dönerek sizi engellemelidir.

## 📦 Senaryo 4: Sürüm Merkezi (Phase 3)
**Amaç:** Yazılım güncellemelerinin sorunsuz dağıtıldığını doğrulamak.

1.  **Yeni Yayın:** `/master/releases/create` sayfasından yeni bir `.zip` dosyası yükleyin ve sürümü `v1.2.0` yapın.
2.  **Güncelleme Kontrolü:** İstemci tarafı API'sı `/check-update` üzerinden `v1.1.0` versiyonunu gönderdiğinde, sistemin otomatik olarak `v1.2.0` paketini önerdiğini görün.

## 📊 Senaryo 5: BI & Analitik (Phase 8 & 10)
**Amaç:** Dashboard verilerinin doğruluğunu ve hızını test etmek.

1.  **MRR Testi:** Yeni bir **Aylık (Monthly)** lisans oluşturun, fiyatını `100 USD` yapın. Dashboard'u yenilediğinizde MRR kartının arttığını görün.
2.  **Caching:** Dashboard'u saniyeler içinde defalarca yenileyin. Verilerin çok hızlı (saniyeler altında) geldiğini, çünkü Cache'ten (Phase 10) okunduğunu fark edin.

## 📢 Senaryo 6: Global İletişim (Phase 4 & 9)
**Amaç:** Bildirimlerin hedefe ulaştığını doğrulamak.

1.  **Kritik Duyuru:** Bir duyuru oluşturun, `Flaş Haber` seçeneğini işaretleyin.
2.  **Görsel Doğrulama:** Master Panel ana ekranında bu duyurunun kıp kırmızı ve "Flaş" etiketiyle yanıp söndüğünü (Pulse Animasyonu) görün.
3.  **API Check:** `/api/master/announcements` endpoint'ini çağırın. Flaş haberin listenin en başında geldiğini doğrulayın.

## 🧹 Senaryo 7: Otomatik Temizlik (Phase 9)
**Amaç:** Sistemin kendi kendini temizleyebildiğini test etmek.

1.  **Manuel Tetikleme:** Terminalde `php artisan master:cleanup` komutunu çalıştırın.
2.  **Sonuç:** 30 günden eski logların `storage/app/logs/archived_...` altına taşındığını ve veritabanından silindiğini doğrulayın.

---
**Tebrikler!** Bu senaryoları başarıyla tamamladıysanız, MIONEX Master Panel üretim hattına (Live) girmeye her şeyiyle hazırdır. 🚀
