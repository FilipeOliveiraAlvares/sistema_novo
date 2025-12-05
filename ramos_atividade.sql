-- Lista completa de ramos de atividade para empresas
-- Execute este SQL no seu banco de dados

INSERT INTO ramos (nome, slug, ativo, ordem) VALUES
-- Automotivo
('Mecânica Automotiva', 'mecanica-automotiva', 1, 1),
('Auto Elétrica', 'auto-eletrica', 1, 2),
('Funilaria e Pintura', 'funilaria-e-pintura', 1, 3),
('Lava Jato e Detailing', 'lava-jato-e-detailing', 1, 4),
('Vidros Automotivos', 'vidros-automotivos', 1, 5),
('Pneus e Rodas', 'pneus-e-rodas', 1, 6),
('Acessórios Automotivos', 'acessorios-automotivos', 1, 7),
('Concessionárias', 'concessionarias', 1, 8),

-- Construção e Reforma
('Construção Civil', 'construcao-civil', 1, 10),
('Reformas e Acabamentos', 'reformas-e-acabamentos', 1, 11),
('Pintura Predial', 'pintura-predial', 1, 12),
('Encanamento e Hidráulica', 'encanamento-e-hidraulica', 1, 13),
('Elétrica e Instalações', 'eletrica-e-instalacoes', 1, 14),
('Coberturas e Telhados', 'coberturas-e-telhados', 1, 15),
('Piscinas e Áreas de Lazer', 'piscinas-e-areas-de-lazer', 1, 16),
('Jardins e Paisagismo', 'jardins-e-paisagismo', 1, 17),
('Serralheria e Solda', 'serralheria-e-solda', 1, 18),
('Marcenaria e Carpintaria', 'marcenaria-e-carpintaria', 1, 19),

-- Alimentação
('Restaurantes', 'restaurantes', 1, 20),
('Lanchonetes e Fast Food', 'lanchonetes-e-fast-food', 1, 21),
('Padarias', 'padarias', 1, 22),
('Confeitarias e Doces', 'confeitarias-e-doces', 1, 23),
('Pizzarias', 'pizzarias', 1, 24),
('Açougues e Peixarias', 'acougues-e-peixarias', 1, 25),
('Hortifruti', 'hortifruti', 1, 26),
('Supermercados', 'supermercados', 1, 27),
('Mercearias e Minimercados', 'mercearias-e-minimercados', 1, 28),
('Bebidas e Bebedouros', 'bebidas-e-bebedouros', 1, 29),
('Food Trucks', 'food-trucks', 1, 30),
('Cafeterias', 'cafeterias', 1, 31),

-- Saúde e Bem-estar
('Clínicas Médicas', 'clinicas-medicas', 1, 32),
('Clínicas Odontológicas', 'clinicas-odontologicas', 1, 33),
('Fisioterapia', 'fisioterapia', 1, 34),
('Psicologia', 'psicologia', 1, 35),
('Nutrição', 'nutricao', 1, 36),
('Farmácias', 'farmacias', 1, 37),
('Drogarias', 'drogarias', 1, 38),
('Óticas', 'oticas', 1, 39),
('Laboratórios de Análises', 'laboratorios-de-analises', 1, 40),
('Acupuntura', 'acupuntura', 1, 41),
('Quiropraxia', 'quiropraxia', 1, 42),
('Massoterapia', 'massoterapia', 1, 43),

-- Beleza e Estética
('Salões de Beleza', 'saloes-de-beleza', 1, 44),
('Barbearias', 'barbearias', 1, 45),
('Estética Facial e Corporal', 'estetica-facial-e-corporal', 1, 46),
('Depilação', 'depilacao', 1, 47),
('Unhas e Manicure', 'unhas-e-manicure', 1, 48),
('Maquiagem', 'maquiagem', 1, 49),
('Tatuagens e Piercings', 'tatuagens-e-piercings', 1, 50),

-- Educação
('Escolas', 'escolas', 1, 51),
('Cursos e Treinamentos', 'cursos-e-treinamentos', 1, 52),
('Aulas Particulares', 'aulas-particulares', 1, 53),
('Idiomas', 'idiomas', 1, 54),
('Música e Instrumentos', 'musica-e-instrumentos', 1, 55),
('Dança', 'danca', 1, 56),
('Artes e Artesanato', 'artes-e-artesanato', 1, 57),

-- Tecnologia
('Informática e TI', 'informatica-e-ti', 1, 58),
('Assistência Técnica em Informática', 'assistencia-tecnica-em-informatica', 1, 59),
('Venda de Equipamentos de Informática', 'venda-de-equipamentos-de-informatica', 1, 60),
('Desenvolvimento de Software', 'desenvolvimento-de-software', 1, 61),
('Telecomunicações', 'telecomunicacoes', 1, 62),
('Celulares e Acessórios', 'celulares-e-acessorios', 1, 63),
('Eletrônicos', 'eletronicos', 1, 64),
('Eletrodomésticos', 'eletrodomesticos', 1, 65),

-- Comércio
('Vestuário e Moda', 'vestuario-e-moda', 1, 66),
('Calçados', 'calcados', 1, 67),
('Acessórios e Bijuterias', 'acessorios-e-bijuterias', 1, 68),
('Móveis e Decoração', 'moveis-e-decoracao', 1, 69),
('Cama, Mesa e Banho', 'cama-mesa-e-banho', 1, 70),
('Casa e Jardim', 'casa-e-jardim', 1, 71),
('Brinquedos e Games', 'brinquedos-e-games', 1, 72),
('Livrarias e Papelarias', 'livrarias-e-papelarias', 1, 73),
('Presentes e Lembranças', 'presentes-e-lembrancas', 1, 74),

-- Serviços Profissionais
('Advocacia', 'advocacia', 1, 75),
('Contabilidade', 'contabilidade', 1, 76),
('Arquitetura', 'arquitetura', 1, 77),
('Engenharia', 'engenharia', 1, 78),
('Design Gráfico', 'design-grafico', 1, 79),
('Marketing e Publicidade', 'marketing-e-publicidade', 1, 80),
('Consultoria', 'consultoria', 1, 81),
('Recursos Humanos', 'recursos-humanos', 1, 82),
('Tradução e Interpretação', 'traducao-e-interpretacao', 1, 83),

-- Transporte
('Transporte de Cargas', 'transporte-de-cargas', 1, 84),
('Transporte de Passageiros', 'transporte-de-passageiros', 1, 85),
('Mudanças', 'mudancas', 1, 86),
('Locação de Veículos', 'locacao-de-veiculos', 1, 87),
('Taxi e Aplicativos', 'taxi-e-aplicativos', 1, 88),

-- Turismo e Hospedagem
('Hotéis', 'hoteis', 1, 89),
('Pousadas', 'pousadas', 1, 90),
('Agências de Viagem', 'agencias-de-viagem', 1, 91),
('Passeios e Turismo', 'passeios-e-turismo', 1, 92),

-- Esportes e Lazer
('Academias', 'academias', 1, 93),
('Personal Trainer', 'personal-trainer', 1, 94),
('Artes Marciais', 'artes-marciais', 1, 95),
('Esportes Aquáticos', 'esportes-aquaticos', 1, 96),
('Equipamentos Esportivos', 'equipamentos-esportivos', 1, 97),
('Parques e Recreação', 'parques-e-recreacao', 1, 98),

-- Serviços Domésticos
('Limpeza e Conservação', 'limpeza-e-conservacao', 1, 99),
('Dedetização e Controle de Pragas', 'dedetizacao-e-controle-de-pragas', 1, 100),
('Jardinagem', 'jardinagem', 1, 101),
('Cuidadores e Babás', 'cuidadores-e-babas', 1, 102),
('Lavanderias', 'lavanderias', 1, 103),
('Passadeiras', 'passadeiras', 1, 104),

-- Manutenção e Reparos
('Manutenção Predial', 'manutencao-predial', 1, 105),
('Manutenção de Equipamentos', 'manutencao-de-equipamentos', 1, 106),
('Conserto de Eletrodomésticos', 'conserto-de-eletrodomesticos', 1, 107),
('Chaveiros', 'chaveiros', 1, 108),
('Sapateiros', 'sapateiros', 1, 109),
('Costureiras e Alfaiates', 'costureiras-e-alfaiates', 1, 110),

-- Comunicação e Entretenimento
('Fotografia', 'fotografia', 1, 111),
('Vídeo e Cinema', 'video-e-cinema', 1, 112),
('Eventos e Festas', 'eventos-e-festas', 1, 113),
('Som e Iluminação', 'som-e-iluminacao', 1, 114),
('Buffet e Catering', 'buffet-e-catering', 1, 115),
('Locação de Equipamentos para Eventos', 'locacao-de-equipamentos-para-eventos', 1, 116),
('DJs e Música ao Vivo', 'djs-e-musica-ao-vivo', 1, 117),

-- Animais
('Clínicas Veterinárias', 'clinicas-veterinarias', 1, 118),
('Pet Shops', 'pet-shops', 1, 119),
('Adestramento', 'adestramento', 1, 120),
('Hospedagem para Animais', 'hospedagem-para-animais', 1, 121),
('Tosadores', 'tosadores', 1, 122),

-- Imóveis
('Imobiliárias', 'imobiliarias', 1, 123),
('Corretores de Imóveis', 'corretores-de-imoveis', 1, 124),
('Administração de Condomínios', 'administracao-de-condominios', 1, 125),

-- Financeiro
('Bancos', 'bancos', 1, 126),
('Financeiras', 'financeiras', 1, 127),
('Seguros', 'seguros', 1, 128),
('Consórcios', 'consorcios', 1, 129),
('Câmbio', 'cambio', 1, 130),

-- Indústria e Produção
('Indústria Alimentícia', 'industria-alimenticia', 1, 131),
('Indústria Têxtil', 'industria-textil', 1, 132),
('Indústria Química', 'industria-quimica', 1, 133),
('Indústria Metalúrgica', 'industria-metalurgica', 1, 134),
('Indústria de Plásticos', 'industria-de-plasticos', 1, 135),

-- Agricultura
('Agricultura', 'agricultura', 1, 136),
('Pecuária', 'pecuaria', 1, 137),
('Agronegócio', 'agronegocio', 1, 138),
('Equipamentos Agrícolas', 'equipamentos-agricolas', 1, 139),

-- Segurança
('Segurança Privada', 'seguranca-privada', 1, 140),
('Alarmes e Monitoramento', 'alarmes-e-monitoramento', 1, 141),
('Câmeras e Sistemas de Segurança', 'cameras-e-sistemas-de-seguranca', 1, 142),

-- Energia e Utilidades
('Energia Solar', 'energia-solar', 1, 143),
('Gás e Combustíveis', 'gas-e-combustiveis', 1, 144),
('Água e Saneamento', 'agua-e-saneamento', 1, 145),

-- Outros Serviços
('Funerárias', 'funerarias', 1, 146),
('Cartórios', 'cartorios', 1, 147),
('Perícias', 'pericias', 1, 148),
('Auditoria', 'auditoria', 1, 149),
('Comunicação e Mídia', 'comunicacao-e-midia', 1, 150),
('Editoras e Gráficas', 'editoras-e-graficas', 1, 151),
('Material de Construção', 'material-de-construcao', 1, 152),
('Ferragens', 'ferragens', 1, 153),
('Tintas e Vernizes', 'tintas-e-vernizes', 1, 154),
('Outros', 'outros', 1, 999)
ON DUPLICATE KEY UPDATE nome=nome;

