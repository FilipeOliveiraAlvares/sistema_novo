-- Script para adicionar Soft Deletes na tabela spots
-- Execute este SQL no seu banco de dados (phpMyAdmin, Adminer, etc.)

-- Adicionar coluna deleted_at para soft deletes
ALTER TABLE spots 
    ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;

-- Criar índice para melhorar performance de consultas que excluem registros deletados
ALTER TABLE spots
    ADD INDEX idx_deleted_at (deleted_at);

-- Nota: Com soft deletes habilitado no model, os registros não serão fisicamente deletados,
-- apenas marcados com a data/hora em deleted_at. Isso permite recuperar dados posteriormente.

