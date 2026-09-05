/**
 * Pembuka halaman TJR v5: sampul buku yang terbuka, kira kira 1,6 detik.
 *
 * Tanpa pustaka animasi. Timeline dipegang Web Animations API, jadi tetap bisa
 * diatur bertahap seperti GSAP tapi tambahan bobotnya nol. Semua keadaan awal
 * dipasang lewat kelas .intro di elemen html, yang dipasang skrip sebaris di
 * functions.php. Kalau JavaScript mati, kelas itu tidak pernah ada dan halaman
 * langsung tampil utuh.
 *
 * Sampulnya berputar pada sumbu kiri, persis seperti membuka jurnal. Yang bikin
 * terbaca sebagai buku bukan rotasinya, tapi dua hal kecil: bayangan yang
 * menggelap di sampul waktu dia berputar menjauh dari cahaya, dan bayangan
 * jatuh di halaman yang menipis sambil terbuka.
 */
(function () {
  'use strict';

  var root = document.documentElement;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var E = 'cubic-bezier(.16,1,.3,1)';

  var tirai = document.getElementById('tirai');
  var sampul = document.getElementById('sampul');
  var jatuh = document.getElementById('jatuh');
  var mark = tirai ? tirai.querySelector('img') : null;
  var lembar = document.querySelector('.wp-site-blocks');
  var anims = [];

  // Judul utama, boleh berupa h1 asli atau paragraf bergaya display.
  var h1 = document.querySelector('.hero h1, .hero .d-xxl');

  // Judul dipecah per kata supaya bisa naik bergantian dari balik garis.
  if (h1 && !h1.querySelector('.kata')) {
    h1.innerHTML = h1.textContent
      .trim()
      .split(/\s+/)
      .map(function (k) {
        return '<span class="kata"><i>' + k + '</i></span>';
      })
      .join(' ');
  }

  /**
   * Jaring pengaman. Kalau animasi tidak sempat jalan, misalnya tab ada di
   * latar belakang atau browser membekukan timeline, semuanya dipaksa ke
   * keadaan akhir supaya halaman tidak pernah tertinggal blank.
   */
  function selesai() {
    root.classList.remove('intro');
    anims.forEach(function (a) {
      try {
        a.finish();
      } catch (e) {}
    });
    if (tirai && tirai.parentNode) {
      tirai.remove();
    }
  }

  setTimeout(selesai, 4200);
  window.addEventListener('pagehide', selesai);

  if (!tirai || !sampul || !mark || reduce || !document.body.animate || document.visibilityState !== 'visible') {
    selesai();
    return;
  }

  function gerak(el, dari, ke, ms, tunda) {
    if (!el) {
      return;
    }
    anims.push(
      el.animate([dari, ke], {
        duration: ms,
        delay: tunda || 0,
        easing: E,
        fill: 'both'
      })
    );
  }

  function bukaHalaman() {
    var t = 0;

    gerak(
      document.querySelector('.bar'),
      { opacity: 0, transform: 'translateY(-12px)' },
      { opacity: 1, transform: 'none' },
      700,
      t
    );

    gerak(
      document.querySelector('.hero .lbl'),
      { opacity: 0, transform: 'translateY(14px)' },
      { opacity: 1, transform: 'none' },
      700,
      t + 90
    );

    document.querySelectorAll('.hero .kata > i').forEach(function (k, i) {
      anims.push(
        k.animate([{ translate: '0 106%' }, { translate: '0 0' }], {
          duration: 900,
          delay: t + 150 + i * 80,
          easing: E,
          fill: 'both'
        })
      );
    });

    gerak(
      document.querySelector('.hero-aksi'),
      { opacity: 0, transform: 'translateY(16px)' },
      { opacity: 1, transform: 'none' },
      760,
      t + 420
    );

    var panggung = document.querySelector('.panggung');
    if (panggung) {
      anims.push(
        panggung.animate(
          [
            { opacity: 0, clipPath: 'inset(100% 0 0 0)' },
            { opacity: 1, clipPath: 'inset(0 0 0 0)' }
          ],
          { duration: 1100, delay: t + 380, easing: E, fill: 'both' }
        )
      );

      var foto = panggung.querySelector('img');
      if (foto) {
        anims.push(
          foto.animate([{ transform: 'scale(1.14)' }, { transform: 'scale(1)' }], {
            duration: 1600,
            delay: t + 380,
            easing: E,
            fill: 'both'
          })
        );
      }
    }

    // Kartu sesi punya animasi apung yang jalan terus, jadi transform-nya tidak
    // boleh dipakai di sini. Cukup opacity, biar tidak saling menimpa.
    gerak(
      document.querySelector('.kartu-sesi'),
      { opacity: 0 },
      { opacity: 1 },
      820,
      t + 900
    );

    var cetakan = document.querySelector('.hero > .cetakan');
    if (cetakan) {
      anims.push(
        cetakan.animate(
          [
            { opacity: 0, transform: 'translateY(26px) rotate(-22deg)' },
            { opacity: 1, transform: 'none' }
          ],
          { duration: 950, delay: t + 1020, easing: E, fill: 'both' }
        )
      );
    }

    setTimeout(selesai, 1400);
  }

  var mulai = Date.now();
  var siap = document.fonts ? document.fonts.ready : Promise.resolve();

  // Jangan sandera halaman kalau file huruf lama datangnya.
  var batas = new Promise(function (r) {
    setTimeout(r, 1400);
  });

  Promise.race([siap, batas]).then(function () {
    var tahan = Math.max(0, 420 - (Date.now() - mulai));

    // 1. logo naik pelan di sampul yang masih tertutup
    anims.push(
      mark.animate(
        [
          { opacity: 0, transform: 'translateY(12px) scale(.96)' },
          { opacity: 1, transform: 'none' }
        ],
        { duration: 660, easing: E, fill: 'both' }
      )
    );

    setTimeout(function () {
      var BUKA = 1180; // lama sampul membuka
      var LAJU = 'cubic-bezier(.58,.04,.22,1)'; // berat di awal, ringan di akhir

      // 2. sampul berputar pada sumbu kiri
      var sa = sampul.animate([{ transform: 'rotateY(0deg)' }, { transform: 'rotateY(-118deg)' }], {
        duration: BUKA,
        easing: LAJU,
        fill: 'both'
      });
      anims.push(sa);

      // logo ikut memudar begitu sampul mulai miring
      anims.push(
        mark.animate([{ opacity: 1 }, { opacity: 0 }], {
          duration: 420,
          delay: 120,
          easing: 'linear',
          fill: 'both'
        })
      );

      // 3. sampul menggelap sambil berputar menjauh dari cahaya
      anims.push(
        sampul.animate([{ opacity: 0 }, { opacity: 0.1, offset: 0.25 }, { opacity: 0.62 }], {
          duration: BUKA,
          easing: 'linear',
          fill: 'both',
          pseudoElement: '::after'
        })
      );

      // 4. bayangan sampul yang jatuh di halaman, muncul lalu menipis
      if (jatuh) {
        anims.push(
          jatuh.animate(
            [
              { opacity: 0 },
              { opacity: 0.9, offset: 0.22 },
              { opacity: 0.45, offset: 0.6 },
              { opacity: 0 }
            ],
            { duration: BUKA + 260, easing: 'linear', fill: 'both' }
          )
        );
      }

      // 5. halaman ikut turun sedikit, seperti kertas yang mendarat
      if (lembar) {
        anims.push(
          lembar.animate(
            [{ transform: 'scale(.986) translateY(8px)' }, { transform: 'none' }],
            { duration: BUKA + 320, easing: E, fill: 'both' }
          )
        );
      }

      sa.onfinish = function () {
        if (tirai.parentNode) {
          tirai.remove();
        }
      };

      // isi halaman mulai sebelum sampulnya selesai, biar tidak terasa berurutan
      setTimeout(bukaHalaman, 340);
    }, tahan + 520);
  });
})();

/**
 * Tombol WhatsApp mengambang, muncul setelah halaman digulir 60 persen layar.
 */
(function () {
  'use strict';

  var fab = document.getElementById('fab');
  if (!fab) {
    return;
  }

  function tampil() {
    fab.classList.toggle('tampil', window.scrollY > window.innerHeight * 0.6);
  }

  window.addEventListener('scroll', tampil, { passive: true });
  tampil();
})();
