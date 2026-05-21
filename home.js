document.addEventListener('DOMContentLoaded', () => {
  const grid = document.querySelector('.book-grid');
  if (!grid) return;

  const featuredTitles = [
    'Щасливці',
    'Привиди будинку на пагорбі',
    'Аліса в країні див'
  ];

  fetch('get_products.php')
    .then(response => response.json())
    .then(data => {
      if (!Array.isArray(data)) return console.error('Invalid products response', data);
      if (data.length === 0) {
        grid.innerHTML = '<p>Поки що нема книг.</p>';
        return;
      }

      const featured = featuredTitles.map(title => {
        const product = data.find(p => String(p.title).toLowerCase() === title.toLowerCase());
        return product || createPlaceholder(title);
      });

      grid.innerHTML = featured.map(p => {
        const img = formatImagePath(p.image);
        const price = (p.price !== undefined && p.price !== null) ? Number(p.price).toFixed(2) : '';
        return `
          <div class="book-card" data-id="${escapeHtml(p.id || '')}">
            <div class="book-image" style="background-image: url('${escapeHtml(img)}')"></div>
            <div class="book-info">
              <span class="category">${escapeHtml(p.category || '')}</span>
              <h3>${escapeHtml(p.title || '')}</h3>
              <p class="author">${escapeHtml(p.author || p.publisher || '')}</p>
              <div class="card-footer">
                <span class="price">${price} ₴</span>
                <button class="buy-icon" data-id="${escapeHtml(p.id || '')}">+</button>
              </div>
            </div>
          </div>`;
      }).join('');
    })
    .catch(err => console.error('Fetch products error', err));
});

function formatImagePath(img) {
  if (!img) return 'images/default.jpg';
  if (img.startsWith('http://') || img.startsWith('https://') || img.startsWith('/')) return img;
  if (img.startsWith('upload/') || img.startsWith('images/')) return img;
  return 'upload/' + img;
}

function escapeHtml(unsafe) {
  return String(unsafe)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function createPlaceholder(title) {
  return {
    id: '',
    title,
    author: '',
    publisher: '',
    category: 'Рекомендовано',
    price: '',
    image: 'images/default.jpg'
  };
}
