/**
 * Pembuka halaman TJR v5, kira kira 1,6 detik.
 *
 * Tanpa pustaka animasi. Timeline dipegang Web Animations API, jadi tetap bisa
 * diatur bertahap seperti GSAP tapi tambahan bobotnya nol. Semua keadaan awal
 * dipasang lewat kelas .intro di elemen html, yang dipasang skrip sebaris di
 * functions.php. Kalau JavaScript mati, kelas itu tidak pernah ada dan halaman
 * langsung tampil utuh.
 */
(function () {
  'use strict';

  var root = document.documentElement;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var E = 'cubic-bezier(.16,1,.3,1)';

  var tirai = document.getElementById('tirai');
  var anims = [];

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
    if (tirai) {
      tirai.style.display = 'none';
    }
  }

  setTimeout(selesai, 4200);
  window.addEventListener('pagehide', selesai);

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

  if (!tirai || reduce || !document.body.animate || document.visibilityState !== 'visible') {
    selesai();
  } else {
    mulaiTirai();
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

      var foto = panggung.querySelector('.wp-block-cover__image-background, img');
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

    gerak(
      document.querySelector('.kartu-sesi'),
      { opacity: 0, transform: 'translateY(20px) scale(.97)' },
      { opacity: 1, transform: 'none' },
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

  function mulaiTirai() {
    var mark = tirai.querySelector('img');
    var mulai = Date.now();
    var siap = document.fonts ? document.fonts.ready : Promise.resolve();

    // Jangan sandera halaman kalau file huruf lama datangnya.
    var batas = new Promise(function (r) {
      setTimeout(r, 1400);
    });

    Promise.race([siap, batas]).then(function () {
      var tahan = Math.max(0, 420 - (Date.now() - mulai));

      if (mark) {
        mark.animate([{ opacity: 0, transform: 'translateY(10px)' }, { opacity: 1, transform: 'none' }], {
          duration: 620,
          easing: E,
          fill: 'both'
        });
      }

      setTimeout(function () {
        var ta = tirai.animate(
          [{ clipPath: 'inset(0 0 0 0)' }, { clipPath: 'inset(0 0 100% 0)' }],
          { duration: 820, easing: 'cubic-bezier(.7,0,.2,1)', fill: 'both' }
        );
        anims.push(ta);
        ta.onfinish = function () {
          tirai.style.display = 'none';
        };
        bukaHalaman();
      }, tahan + 460);
    });
  }
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
