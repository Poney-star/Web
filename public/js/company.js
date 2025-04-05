function getSelectedFilters(filters = {}) {
    document.querySelectorAll('.filter-checkbox:checked').forEach(checkbox => {
      // Trouve la classe de catégorie (filter-xxx)
      const categoryClass = Array.from(checkbox.classList).find(cls => 
        cls !== 'filter-checkbox'
      );
      if (categoryClass) {
        const category = categoryClass;
        if (!filters[category]) {
          filters[category] = [];
        }
        console.log(category);
        // Convertit en nombre si c'est une durée
        const value = category === 'note' 
          ? Number(checkbox.value) 
          : checkbox.closest('.checkbox-label').textContent.trim();
        filters[category].push(value);
      }
    });
    console.log(filters);
    return filters;
  }

  async function listCompanies(filters) {
    try {
      const response = await fetch('../app/controllers/AsyncController.php', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' 
        },
        body: JSON.stringify({
          action: 'company', 
          filters: filters
        })
      }); 
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const html = await response.text();
      document.querySelector('.cards-grid').innerHTML = html;
    } catch (error) {
      console.error('Erreur:', error);
    }
  }

  // Simple JavaScript for accordion functionality
document.addEventListener('DOMContentLoaded', function() {
    const accordionBtns = document.querySelectorAll('.accordion-btn');
    
    accordionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
        this.classList.toggle('active');
        const content = this.nextElementSibling;
        
        if (content.style.maxHeight) {
            content.style.maxHeight = null;
            this.querySelector('.accordion-icon').textContent = '▼';
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            this.querySelector('.accordion-icon').textContent = '▲';
        }
        });
    });

    const checkbox = document.querySelectorAll('.filter-checkbox');

    checkbox.forEach(check => {
        check.addEventListener('click', function() {
            if (this.classList.contains(':checked')) {
                this.classList.remove(':checked');
            } else {
                this.classList.add(':checked');
            }
        });
    });

    // Écoute le clic sur le bouton Appliquer
    const filterConfirm = document.querySelector('.filter-confirm')
    filterConfirm.addEventListener('click', () => {
    filters = getSelectedFilters();
    listCompanies(filters);
    });
    
    const sortSelect = document.querySelector('.sort-select');

    // Ajouter un écouteur d'événement pour le changement de sélection
    sortSelect.addEventListener('change', function() {
      const filters = {
        'sortOption' : "Ordre alphabétique" == this.value ? "entreprise.Nom" : (this.value == "Mieux notés" ? "entreprise.Note" : "offre.Date_Mise_En_Ligne")
      };
      filters = getSelectedFilters(filters);
      listCompanies(filters);
    });
    
});