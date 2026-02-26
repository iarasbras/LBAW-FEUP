document.addEventListener('DOMContentLoaded', () => {
    console.log('App.js carregado com sucesso!');

   // ==========================================
    // 1. ADICIONAR AO CARRINHO (Catálogo)
    // ==========================================
    const addForms = document.querySelectorAll('.ajax-cart-add');
    
    if (addForms.length === 0) console.warn('Nenhum formulário .ajax-cart-add encontrado nesta página.');

    addForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); 

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerText : 'Adicionar';
            
            // Feedback visual imediato
            if(submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = "...";
            }

            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) throw new Error('Erro rede');

                const data = await response.json();

                if (data.success) {
                    // Atualiza contador
                    const countEl = document.getElementById('cart-items-count');
                    if(countEl) countEl.innerText = data.cartCount;

                    // Feedback botão
                    if(submitBtn) {
                        submitBtn.innerText = "Adicionado!"; // Mensagem curta
                        
                        setTimeout(() => {
                            submitBtn.innerText = originalText;
                            submitBtn.disabled = false;
                        }, 100); 
                    }
                }
            } catch (error) {
                console.error('Erro ao adicionar:', error);
                if(submitBtn) {
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                }
            }
        });
    });

    // ==========================================
    // 2. ATUALIZAR QUANTIDADE (Carrinho)
    // ==========================================
    document.querySelectorAll('.ajax-cart-update').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const bookId = formData.get('book_id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    const countEl = document.getElementById('cart-items-count');
                    if(countEl) countEl.innerText = data.cartCount;
                    
                    const subEl = document.getElementById(`subtotal-${bookId}`);
                    if(subEl) subEl.innerText = data.newSubtotal;
                    
                    const totalEl = document.getElementById('cart-total-value');
                    if(totalEl) totalEl.innerText = data.newTotal;
                }
            } catch (error) { console.error('Erro update:', error); }
        });
    });

    // ==========================================
    // 3. REMOVER ITEM (Carrinho)
    // ==========================================
    document.querySelectorAll('.ajax-cart-remove').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const bookId = formData.get('book_id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    const row = document.getElementById(`cart-row-${bookId}`);
                    if(row) row.remove();
                    
                    const countEl = document.getElementById('cart-items-count');
                    if(countEl) countEl.innerText = data.cartCount;
                    
                    if (data.isEmpty) {
                        location.reload(); 
                    } else {
                        const totalEl = document.getElementById('cart-total-value');
                        if(totalEl) totalEl.innerText = data.newTotal;
                    }
                }
            } catch (error) { console.error('Erro remove:', error); }
        });
    });

    // ==========================================
    // 4. FILTROS DE CATÁLOGO (AJAX)
    // ==========================================
    const catalogFiltersForm = document.querySelector('.catalog-filters');
    const catalogResultsContainer = document.getElementById('catalog-results');

    if (catalogFiltersForm && catalogResultsContainer) {
        
        // Função para procurar
        const fetchCatalog = async (url) => {
            catalogResultsContainer.style.opacity = '0.5'; // Feedback visual

            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                if (response.ok) {
                    const html = await response.text();
                    catalogResultsContainer.innerHTML = html;
                    
                    const newAddForms = catalogResultsContainer.querySelectorAll('.ajax-cart-add');
                    window.history.pushState(null, '', url);
                }
            } catch (error) {
                console.error('Erro ao filtrar:', error);
            } finally {
                catalogResultsContainer.style.opacity = '1';
            }
        };

        // Evento: Submit do formulário (Pesquisa)
        catalogFiltersForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            fetchCatalog(`${this.action}?${params}`);
        });

        // Evento: Mudança nos Selects (Categoria e Ordenação)
        const selects = catalogFiltersForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                // Dispara o evento de submit do formulário manualmente
                catalogFiltersForm.dispatchEvent(new Event('submit'));
            });
        });

        // Evento: Paginação (Links)
        catalogResultsContainer.addEventListener('click', function(e) {
            const link = e.target.closest('.pagination a'); 
            if (link) {
                e.preventDefault();
                fetchCatalog(link.href);
                // Scroll para o topo da grelha suavemente
                document.querySelector('.catalog-hero').scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});