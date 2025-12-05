<script>
// ============================================
// SISTEMA DE TOAST NOTIFICATIONS (JavaScript Puro)
// ============================================
function showToast(message, type = 'info', duration = 5000) {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
    };
    
    toast.innerHTML = `
        <span class="toast-icon">${icons[type] || icons.info}</span>
        <span class="toast-message">${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()" aria-label="Fechar">×</button>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// ============================================
// LOADING SPINNER
// ============================================
function showLoading() {
    if (document.getElementById('loading-overlay')) return;
    
    const overlay = document.createElement('div');
    overlay.id = 'loading-overlay';
    overlay.className = 'loading-overlay';
    overlay.innerHTML = '<div class="spinner"></div>';
    document.body.appendChild(overlay);
}

function hideLoading() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) overlay.remove();
}

// ============================================
// MÁSCARAS DE ENTRADA
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para CPF/CNPJ
    const cpfCnpjInput = document.getElementById('cpf_cnpj');
    if (cpfCnpjInput) {
        cpfCnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                // CPF: 000.000.000-00
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            } else {
                // CNPJ: 00.000.000/0000-00
                value = value.replace(/(\d{2})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1/$2');
                value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });
    }

    // Máscara para telefone/WhatsApp: (00) 00000-0000
    const telefoneInputs = document.querySelectorAll('#telefone, #whatsapp');
    telefoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            } else {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    });

    // Máscara para CEP: 00000-000
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }

    // Preview de logo
    const logoInput = document.getElementById('logo');
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            previewLogo(e.target);
        });
    }

    // Loading em formulários
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            showLoading();
        });
    });

    // Converter mensagens flash em toast
    const flashMessage = document.querySelector('[data-flash-message]');
    if (flashMessage) {
        const message = flashMessage.textContent.trim();
        const type = flashMessage.dataset.flashType || 'info';
        if (message) {
            showToast(message, type);
        }
    }
});

// ============================================
// PREVIEW DE LOGO
// ============================================
function previewLogo(input) {
    const preview = document.getElementById('logo-preview');
    const container = document.getElementById('logo-preview-container');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (preview) {
                preview.src = e.target.result;
                if (container) container.style.display = 'block';
            } else {
                // Cria preview se não existir
                const newContainer = document.createElement('div');
                newContainer.id = 'logo-preview-container';
                newContainer.style.cssText = 'margin-top: 10px;';
                newContainer.innerHTML = `<img id="logo-preview" src="${e.target.result}" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #d1d5db;">`;
                input.parentElement.appendChild(newContainer);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ============================================
// BUSCA E FILTROS NA TABELA
// ============================================
function filterTable() {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const vendedorFilter = document.getElementById('vendedor-filter');
    
    if (!searchInput) return;
    
    const searchValue = searchInput.value.toLowerCase();
    const statusValue = statusFilter ? statusFilter.value : '';
    const vendedorValue = vendedorFilter ? vendedorFilter.value : '';
    
    const table = document.querySelector('table tbody');
    if (!table) return;
    
    const rows = table.querySelectorAll('tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length === 0) return;
        
        // Detecta dinamicamente os índices das colunas
        const nome = (cells[1]?.textContent || '').toLowerCase();
        const slug = (cells[2]?.textContent || '').toLowerCase();
        const categoria = (cells[3]?.textContent || '').toLowerCase();
        
        // Procura pela coluna de status (pode estar em diferentes posições)
        let statusBadge = '';
        let vendedor = '';
        let statusIndex = -1;
        let vendedorIndex = -1;
        
        // Procura pela coluna de status (procura por "Ativo" ou "Inativo")
        for (let i = 0; i < cells.length; i++) {
            const cellText = (cells[i]?.textContent || '').toLowerCase();
            if (cellText.includes('ativo') || cellText.includes('inativo')) {
                statusBadge = cellText;
                statusIndex = i;
            }
            // Se houver filtro de vendedor, procura pela coluna correspondente
            if (vendedorFilter && i === 4) {
                vendedor = cellText;
                vendedorIndex = i;
            }
        }
        
        const matchesSearch = !searchValue || 
            nome.includes(searchValue) || 
            slug.includes(searchValue) || 
            categoria.includes(searchValue);
        
        const matchesStatus = !statusValue || 
            (statusValue === 'ativo' && statusBadge.includes('ativo')) ||
            (statusValue === 'inativo' && statusBadge.includes('inativo'));
        
        const matchesVendedor = !vendedorValue || vendedor.includes(vendedorValue.toLowerCase());
        
        if (matchesSearch && matchesStatus && matchesVendedor) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Atualiza contador de resultados
    const resultCount = document.getElementById('result-count');
    if (resultCount) {
        resultCount.textContent = `${visibleCount} resultado(s) encontrado(s)`;
    }
}

function clearFilters() {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const vendedorFilter = document.getElementById('vendedor-filter');
    
    if (searchInput) searchInput.value = '';
    if (statusFilter) statusFilter.value = '';
    if (vendedorFilter) vendedorFilter.value = '';
    filterTable();
}
</script>

