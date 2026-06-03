document.addEventListener('DOMContentLoaded', () => {
  document.body.classList.add('is-ready');

  // These data attributes are used instead of classes so the CSS can change freely.
  const routeForm = document.querySelector('[data-route-form]');
  const origin = document.querySelector('[data-origin]');
  const destination = document.querySelector('[data-destination]');
  const swapButton = document.querySelector('[data-swap-route]');
  const forms = document.querySelectorAll('form');

  const showLoader = () => {
    let loader = document.querySelector('[data-page-loader]');

    if (!loader) {
      loader = document.createElement('div');
      loader.className = 'page-loader';
      loader.dataset.pageLoader = '';
      loader.innerHTML = '<div class="loader-ring" aria-hidden="true"></div><p>Procesando...</p>';
      document.body.appendChild(loader);
    }

    requestAnimationFrame(() => {
      loader.classList.add('is-visible');
      document.body.classList.add('is-loading');
    });
  };

  // Allows the user to quickly invert origin and destination.
  if (swapButton && origin && destination) {
    swapButton.addEventListener('click', () => {
      const currentOrigin = origin.value;
      origin.value = destination.value;
      destination.value = currentOrigin;
      swapButton.classList.remove('is-spinning');
      void swapButton.offsetWidth;
      swapButton.classList.add('is-spinning');
    });
  }

  // The route should always compare two different departments.
  if (routeForm && origin && destination) {
    routeForm.addEventListener('submit', (event) => {
      if (origin.value === destination.value) {
        event.preventDefault();
        destination.setCustomValidity('El destino debe ser distinto al origen.');
        destination.reportValidity();
        return;
      }

      // Clear previous browser validation when the selection is valid.
      destination.setCustomValidity('');
    });
  }

  forms.forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!event.defaultPrevented && form.checkValidity()) {
        showLoader();
      }
    });
  });
});
