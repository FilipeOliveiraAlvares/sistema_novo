-- Script para criar tabela de cidades e adicionar campos ramo e cidade_id em spots
-- Execute este SQL no seu banco de dados (phpMyAdmin, Adminer, etc.)

-- 1. Criar tabela de cidades
CREATE TABLE IF NOT EXISTS cidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    uf VARCHAR(2) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_uf (uf),
    INDEX idx_slug (slug),
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Adicionar campo ramo na tabela spots
ALTER TABLE spots 
    ADD COLUMN ramo VARCHAR(100) NULL AFTER categoria;

-- 3. Adicionar campo cidade_id na tabela spots
ALTER TABLE spots 
    ADD COLUMN cidade_id INT NULL AFTER ramo;

-- 4. Criar foreign key para cidade_id
ALTER TABLE spots
    ADD CONSTRAINT fk_spots_cidade
        FOREIGN KEY (cidade_id) REFERENCES cidades(id)
        ON DELETE SET NULL;

-- 5. Criar índices para melhorar performance da busca
ALTER TABLE spots
    ADD INDEX idx_ramo (ramo),
    ADD INDEX idx_cidade_id (cidade_id);

-- 6. Inserir algumas cidades de exemplo (opcional - você pode adicionar mais depois)
INSERT INTO cidades (nome, uf, slug, ativo) VALUES
('Ribeirão Preto', 'SP', 'ribeirao-preto-sp', 1),
('Sertãozinho', 'SP', 'sertaozinho-sp', 1),
('Franca', 'SP', 'franca-sp', 1),
('São Paulo', 'SP', 'sao-paulo-sp', 1),
('Campinas', 'SP', 'campinas-sp', 1),
('Araraquara', 'SP', 'araraquara-sp', 1),
('Bauru', 'SP', 'bauru-sp', 1),
('Piracicaba', 'SP', 'piracicaba-sp', 1)
ON DUPLICATE KEY UPDATE nome=nome;

