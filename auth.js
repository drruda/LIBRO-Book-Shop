document.addEventListener('DOMContentLoaded', () => {
  const authButtons = document.querySelector('.auth-buttons');
  if (!authButtons) return;

  const allowedGuestPages = ['homePage.html', 'logIn.html', 'signUp.html', '', 'index.html'];
  const currentPage = window.location.pathname.split('/').pop();

  function isGuestPage() {
    return allowedGuestPages.includes(currentPage);
  }

  function hideRestrictedLinks() {
    const links = document.querySelectorAll('.nav-links li a');
    links.forEach(link => {
      const href = link.getAttribute('href');
      if ([
        'catalog.html',
        'offers.html',
        'admin.html',
        'catalog.php',
        'offers.php',
        'admin.php',
        'add_book.php'
      ].includes(href)) {
        link.parentElement.style.display = 'none';
      }
    });
  }

  fetch('auth_status.php')
    .then(r => r.json())
    .then(data => {
      if (data && data.logged) {
        const roleLabel = (data.role === 'user') ? 'Користувач' : ((data.role === 'admin') ? 'Адмін' : data.role);
        let inner = `
          <div class="user-badge">
            <span class="user-role">${escapeHtml(roleLabel)}</span>
            <span class="user-name">${escapeHtml(data.username)}</span>
          </div>
        `;
        if (data.role === 'admin' && currentPage === 'catalog.php') {
          inner += `<a href="admin.php" class="btn-register btn-admin-panel">Редагувати каталог</a>`;
        }
        inner += `<button type="button" class="btn-register btn-logout">Вихід</button>`;
        authButtons.innerHTML = inner;

        const logoutBtn = authButtons.querySelector('.btn-logout');
        if (logoutBtn) {
          logoutBtn.addEventListener('click', () => {
            fetch('logout.php', { method: 'POST' })
              .then(r => r.json())
              .then(result => {
                if (result && result.success) {
                  window.location.reload();
                } else {
                  alert(result.message || 'Не вдалося вийти.');
                }
              })
              .catch(() => {
                alert('Не вдалося вийти.');
              });
          });
        }
      } else {
        hideRestrictedLinks();
        if (!isGuestPage()) {
          window.location.href = 'homePage.html';
        }
      }
    })
    .catch(() => {
      hideRestrictedLinks();
      if (!isGuestPage()) {
        window.location.href = 'homePage.html';
      }
    });
});

function escapeHtml(unsafe) {
  return String(unsafe)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}
