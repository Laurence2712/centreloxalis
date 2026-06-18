/**
 * Centre l'Oxalis – Scripts interactifs
 */
(function () {
  'use strict';

  // ── Éléments du DOM ────────────────────────────────────────────────────────
  const nav          = document.querySelector('[data-nav]');
  const mobileBtn    = document.querySelector('[data-mobile-btn]');
  const mobileOverlay= document.querySelector('[data-mobile-overlay]');
  const mobileDrawer = document.querySelector('[data-mobile-drawer]');
  const closeMenuBtn = document.querySelector('[data-close-menu]');
  const menuLinks    = document.querySelectorAll('[data-menu-link]');
  const teamGrid     = document.querySelector('[data-team-grid]');
  const filterBtns   = document.querySelectorAll('[data-filter]');
  const specCards    = document.querySelectorAll('[data-spec-card]');
  const modalOverlay = document.querySelector('[data-modal-overlay]');
  const modalInner   = document.querySelector('[data-modal-inner]');
  const closeModalBtn= document.querySelector('[data-close-modal]');

  // Champs du modal
  const modalPhoto   = document.querySelector('[data-modal-photo]');
  const modalName    = document.querySelector('[data-modal-name]');
  const modalRole    = document.querySelector('[data-modal-role]');
  const modalBio     = document.querySelector('[data-modal-bio]');
  const modalPhone   = document.querySelector('[data-modal-phone]');

  // ── Nav: effet au scroll ────────────────────────────────────────────────────
  function updateNav() {
    if (!nav) return;
    if (window.scrollY > 24) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
  }
  window.addEventListener('scroll', updateNav, { passive: true });
  updateNav();

  // ── Reveal au scroll ─────────────────────────────────────────────────────────
  function revealCheck() {
    var h = window.innerHeight || 0;
    var revealAll = h <= 1; // print / zero-viewport contexts
    document.querySelectorAll('.reveal').forEach(function (el) {
      if (el.__shown) return;
      var r = el.getBoundingClientRect();
      if (revealAll || (r.top < h - 60 && r.bottom > 0)) {
        el.__shown = true;
        el.classList.add('is-visible');
      }
    });
  }
  revealCheck();
  window.addEventListener('scroll', revealCheck, { passive: true });
  window.addEventListener('resize', revealCheck, { passive: true });

  // ── Menu mobile ─────────────────────────────────────────────────────────────
  function openMenu() {
    if (!mobileOverlay) return;
    mobileOverlay.classList.add('is-open');
    mobileOverlay.setAttribute('aria-hidden', 'false');
    if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    if (!mobileOverlay) return;
    mobileOverlay.classList.remove('is-open');
    mobileOverlay.setAttribute('aria-hidden', 'true');
    if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (mobileBtn) mobileBtn.addEventListener('click', openMenu);
  if (closeMenuBtn) closeMenuBtn.addEventListener('click', closeMenu);

  // Fermer en cliquant sur l'overlay (hors drawer)
  if (mobileOverlay) {
    mobileOverlay.addEventListener('click', function (e) {
      if (!mobileDrawer || !mobileDrawer.contains(e.target)) {
        closeMenu();
      }
    });
  }

  // Fermer en cliquant un lien du menu
  menuLinks.forEach(function (link) {
    link.addEventListener('click', closeMenu);
  });

  // ── Filtres équipe ───────────────────────────────────────────────────────────
  function applyFilter(filter) {
    // Mettre à jour les boutons
    filterBtns.forEach(function (btn) {
      btn.classList.toggle('is-active', btn.dataset.filter === filter);
    });

    // Afficher / masquer les cartes
    if (!teamGrid) return;
    const cards = teamGrid.querySelectorAll('[data-member-card]');
    cards.forEach(function (card) {
      const cats = card.dataset.categories ? card.dataset.categories.split(',') : [];
      const visible = filter === 'tous' || cats.includes(filter);
      card.style.display = visible ? '' : 'none';
      // Rejoue l'animation reveal si la carte était masquée puis réaffichée
      if (visible && !card.classList.contains('is-visible')) {
        card.classList.add('is-visible');
      }
    });
  }

  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      applyFilter(btn.dataset.filter);
    });
  });

  // ── Spécialités → scroll vers équipe avec filtre ────────────────────────────
  specCards.forEach(function (card) {
    card.addEventListener('click', function () {
      const key = card.dataset.specCard;
      applyFilter(key);

      const teamSection = document.getElementById('equipe');
      if (teamSection) {
        const top = teamSection.getBoundingClientRect().top + window.scrollY - 90;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  // ── Modal membre ─────────────────────────────────────────────────────────────
  function openMember(card) {
    if (!modalOverlay) return;

    const name  = card.dataset.name  || '';
    const role  = card.dataset.role  || '';
    const phone = card.dataset.phone || '';
    const bio   = card.dataset.bio   || '';
    const photo = card.dataset.photo || '';

    if (modalPhoto) {
      modalPhoto.style.backgroundImage = photo ? "url('" + photo + "')" : 'none';
      modalPhoto.setAttribute('aria-label', name);
    }
    if (modalName) modalName.textContent = name;
    if (modalRole) modalRole.textContent = role;
    if (modalBio) {
      modalBio.textContent = bio;
      modalBio.style.display = bio ? '' : 'none';
    }
    if (modalPhone) {
      if (phone) {
        modalPhone.textContent = '📞 ' + phone;
        modalPhone.href = 'tel:' + phone.replace(/\s/g, '');
        modalPhone.classList.remove('hidden');
      } else {
        modalPhone.classList.add('hidden');
      }
    }

    modalOverlay.classList.add('is-open');
    modalOverlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    // Focus sur la fermeture
    if (closeModalBtn) closeModalBtn.focus();
  }

  function closeModal() {
    if (!modalOverlay) return;
    modalOverlay.classList.remove('is-open');
    modalOverlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  // Délégation sur la grille
  if (teamGrid) {
    teamGrid.addEventListener('click', function (e) {
      const card = e.target.closest('[data-member-card]');
      if (card) openMember(card);
    });
  }

  if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);

  // Fermer en cliquant hors du modal
  if (modalOverlay) {
    modalOverlay.addEventListener('click', function (e) {
      if (!modalInner || !modalInner.contains(e.target)) {
        closeModal();
      }
    });
  }

  // Fermer avec Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      if (modalOverlay && modalOverlay.classList.contains('is-open')) closeModal();
      if (mobileOverlay && mobileOverlay.classList.contains('is-open')) closeMenu();
    }
  });

})();
