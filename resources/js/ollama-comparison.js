
function getCookie (name) {
  const regex = new RegExp(`(^| )${name}=([^;]+)`);
  const match = document.cookie.match(regex);
  if (match) {
    return match[2];
  }
}

document.addEventListener('DOMContentLoaded', (event) => {
  const form = document.getElementById('comparison-form');
  const promptField = document.getElementById('prompt');
  const submitButton = form ? form.querySelector('button[type="submit"]') : null;
  const loader = document.getElementById('loader');
  const resultsContainer = document.getElementById('results-container');
  const resultsGrid = document.getElementById('results-grid');

  const charCount = document.getElementById('char-count');

  if (promptField && charCount) {
    promptField.addEventListener('input', (event) => {
      let value = event.currentTarget.value;
      charCount.textContent = value.length;
    });

    promptField.dispatchEvent(new Event('input'));
  }
  if (form) {
    form.addEventListener('submit', (event) => {
      event.preventDefault();

      if (submitButton) {submitButton.disabled = true;}
      if (loader) {loader.style.display = 'inline-block';}

      const formData = new FormData(form);

      fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': getCookie('XSRF-TOKEN')
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success && resultsGrid && resultsContainer) {
            data.results.forEach((result) => {
              const card = document.createElement('div');
              card.className = 'result-card';

              const duration = result.total_duration.toFixed(2);
              let timeClass = 'result-card__time';
              if (duration < 2) {
                timeClass += ' result-card__time--fast';
              } else if (duration > 5) {
                timeClass += ' result-card__time--slow';
              } else {
                timeClass += ' result-card__time--medium';
              }

              card.innerHTML = `
                <div class="result-card__header">
                    <h3 class="result-card__title">${result.model}</h3>
                    <span class="${timeClass}">${duration}</span>
                </div> 
                <div>
                    ${result.response.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>')}
                </div>
              `;

              resultsGrid.appendChild(card);
            });

            resultsContainer.style.display = 'block';

            resultsContainer.scrollIntoView({ behavior: 'smooth' });
          } else if (data.error) {
            alert(data.error);
          }
        })
        .catch(error => {
          alert('An error occurred: ' + error.message);
        })
        .finally(() => {
          if (submitButton) {submitButton.disabled = false;}
          if (loader) {loader.style.display = 'none';}
        });
    });
  }

});

