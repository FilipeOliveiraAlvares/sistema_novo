-- Script para criar tabela de ramos de atividade
-- Execute este SQL no seu banco de dados

CREATE TABLE IF NOT EXISTS ramos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    ativo TINYINT(1) DEFAULT 1,
    ordem INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_ativo (ativo),
    INDEX idx_ordem (ordem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Alterar tabela spots para usar cidade_id como FK para ramos
ALTER TABLE spots 
    MODIFY COLUMN ramo VARCHAR(100) NULL,
    ADD COLUMN ramo_id INT NULL AFTER ramo;

ALTER TABLE spots
    ADD CONSTRAINT fk_spots_ramo
        FOREIGN KEY (ramo_id) REFERENCES ramos(id)
        ON DELETE SET NULL;

ALTER TABLE spots
    ADD INDEX idx_ramo_id (ramo_id);

