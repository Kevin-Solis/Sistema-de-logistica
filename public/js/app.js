document.addEventListener('DOMContentLoaded', () => {
  // These data attributes are used instead of classes so the CSS can change freely.
  const routeForm = document.querySelector('[data-route-form]');
  const origin = document.querySelector('[data-origin]');
  const destination = document.querySelector('[data-destination]');
  const swapButton = document.querySelector('[data-swap-route]');

  // Allows the user to quickly invert origin and destination.
  if (swapButton && origin && destination) {
    swapButton.addEventListener('click', () => {
      const currentOrigin = origin.value;
      origin.value = destination.value;
      destination.value = currentOrigin;
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
});
