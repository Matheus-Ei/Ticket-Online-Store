-- Password for all users: 'Senha@1234'

-- Insert users
INSERT INTO users (name, password_hash, email, role) 
VALUES
  ('Ana Silva', '$2y$10$ZUJrM522XIGdz8kg3i66/OFQyiz.YF7BMnA9X0mPbway2MNsHqNr.', 'ana.silva@email.com', 'seller'),
  ('Bruno Costa', '$2y$10$ZUJrM522XIGdz8kg3i66/OFQyiz.YF7BMnA9X0mPbway2MNsHqNr.', 'bruno.costa@email.com', 'seller'),
  ('Carla Dias', '$2y$10$ZUJrM522XIGdz8kg3i66/OFQyiz.YF7BMnA9X0mPbway2MNsHqNr.', 'carla.dias@email.com', 'seller'),
  ('Daniel Martins', '$2y$10$ZUJrM522XIGdz8kg3i66/OFQyiz.YF7BMnA9X0mPbway2MNsHqNr.', 'daniel.martins@email.com', 'client'),
  ('Eduarda Ferreira', '$2y$10$ZUJrM522XIGdz8kg3i66/OFQyiz.YF7BMnA9X0mPbway2MNsHqNr.', 'eduarda.ferreira@email.com', 'client'),
  ('Fábio Gomes', '$2y$10$ZUJrM522XIGdz8kg3i66/OFQyiz.YF7BMnA9X0mPbway2MNsHqNr.', 'fabio.gomes@email.com', 'client'),
  ('Gabriela Lima', '$2y$10$ZUJrM522XIGdz8kg3i66/OFQyiz.YF7BMnA9X0mPbway2MNsHqNr.', 'gabriela.lima@email.com', 'client'),
  ('Heitor Alves', '$2y$10$ZUJrM522XIGdz8kg3i66/OFQyiz.YF7BMnA9X0mPbway2MNsHqNr.', 'heitor.alves@email.com', 'client'),
  ('Isabela Santos', '$2y$10$ZUJrM522XIGdz8kg3i66/OFQyiz.YF7BMnA9X0mPbway2MNsHqNr.', 'isabela.santos@email.com', 'client'),
  ('João Oliveira', '$2y$10$ZUJrM522XIGdz8kg3i66/OFQyiz.YF7BMnA9X0mPbway2MNsHqNr.', 'joao.oliveira@email.com', 'client');

-- Insert events
INSERT INTO events (name, description, image_url, start_time, end_time, location, ticket_price, ticket_quantity, created_by) 
VALUES
  ('Festival de Rock Clássico', 'Uma noite com as melhores bandas cover de rock dos anos 80 e 90.', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Ftownsquare.media%2Fsite%2F366%2Ffiles%2F2021%2F05%2Fhellfest_music_festival_2018_crowd_surfer.jpg%3Fw%3D1200%26h%3D0%26zc%3D1%26s%3D0%26a%3Dt%26q%3D89&f=1&nofb=1&ipt=91d006576cc1a643ab07f3f1a5502206889c17cb947c05aa37c004bb3423e168', '2025-08-15 19:00:00', '2025-08-16 02:00:00', 'Estádio Municipal', 150.00, 500, 1),
  ('Show Acústico Voz e Violão', 'Show intimista com artistas locais.', 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fflertai.com.br%2Fwp-content%2Fuploads%2F2018%2F01%2Fbranco-mello.jpg&f=1&nofb=1&ipt=9edeef632b115b63733cc7d81b70cd1dd9d04dcba3bc472df53636ff7a0c1d4d', '2025-09-05 20:30:00', '2025-09-05 23:00:00', 'Teatro Central', 80.50, 150, 2),
  ('Feira Gastronômica Sabores do Brasil', 'Experimente pratos típicos de todas as regiões do país.', 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1974&auto=format&fit=crop', '2025-07-26 11:00:00', '2025-07-27 22:00:00', 'Parque da Cidade', 20.00, 2000, 3),
  ('Stand-up Comedy: Noite de Risadas', 'Comediantes renomados em um show de humor imperdível.', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fi.ytimg.com%2Fvi%2FR3FDoW565Lc%2Fmaxresdefault.jpg&f=1&nofb=1&ipt=9f1a1aada84affabd7a80e1b2870c9b3e4f536c422a98860002c24ef6c6d8a30', '2025-08-22 21:00:00', '2025-08-22 23:00:00', 'Clube de Comédia', 60.00, 200, 1),
  ('Concerto de Orquestra Sinfônica', 'Músicas clássicas de Beethoven e Mozart.', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwipr.pr%2Fwp-content%2Fuploads%2F2020%2F03%2Forquesta5-1536x1024.jpg&f=1&nofb=1&ipt=0f8bcf9d97ef080375245bc50629c028d6a3518dba8e0e4c1c2d5611e4fd11d5', '2025-10-10 20:00:00', '2025-10-10 22:00:00', 'Sala de Concertos Principal', 120.00, 300, 2),
  ('Exposição de Arte Moderna', 'Obras de artistas contemporâneos em exibição.', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcultura.jundiai.sp.gov.br%2Fwp-content%2Fuploads%2F2019%2F06%2Fexposicao_pinacoteca_semana_da_arte-1.jpg&f=1&nofb=1&ipt=2be82b9e17a7723fc7b66d7984bc5ce55d9ab2e7ce90f363b3fffb3ec98a5453', '2025-08-01 09:00:00', '2025-08-31 18:00:00', 'Museu de Arte', 30.00, 1000, 3),
  ('Oktoberfest Chapecó', 'Festa alemã com muita cerveja, música e comida típica.', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.chapeco.org%2Fnoticias%2Fwp-content%2Fuploads%2F2019%2F11%2Fc7e4a53b-6fa5-4307-a5ef-87ae423235e3-800x533.jpg&f=1&nofb=1&ipt=be5af0a73b2e24d2342ed3fc835d0a7c74023605951175bc55eb7412a9097181', '2025-10-18 18:00:00', '2025-10-19 03:00:00', 'Centro de Eventos', 50.00, 1500, 1),
  ('Corrida de Rua 5k Pela Saúde', 'Participe e promova um estilo de vida mais saudável.', 'https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=2070&auto=format&fit=crop', '2025-09-14 08:00:00', '2025-09-14 11:00:00', 'Avenida Principal', 45.00, 800, 2),
  ('Workshop de Fotografia para Iniciantes', 'Aprenda os conceitos básicos da fotografia na prática.', 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=1964&auto=format&fit=crop', '2025-08-09 14:00:00', '2025-08-09 18:00:00', 'Estúdio Criativo', 180.00, 30, 3),
  ('Festival de Cinema Independente', 'Exibição de filmes e curtas-metragens de novos diretores.', 'https://images.unsplash.com/photo-1542204165-65bf26472b9b?q=80&w=1974&auto=format&fit=crop', '2025-11-07 17:00:00', '2025-11-09 23:00:00', 'Cinema Paradiso', 25.00, 100, 1),
  ('Festa Junina Beneficente', 'Arraial com comidas típicas, danças e brincadeiras.', 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.kleberpatricio.com.br%2Fwp-content%2Fuploads%2F2017%2F05%2FA-maior-Festa-Junina-Beneficente-da-cidade-acontece-no-Indaiatuba-Clube-dia-3-de-junho-2.jpg&f=1&nofb=1&ipt=e97748a34060a7c7341a46e38a840c81edea368fe86ef7ef15b1a59581bc31b0', '2025-07-20 16:00:00', '2025-07-20 23:00:00', 'Salão Paroquial', 10.00, 400, 2),
  ('Palestra sobre Inteligência Artificial', 'O futuro da tecnologia e seu impacto na sociedade.', 'https://images.unsplash.com/photo-1535223289827-42f1e9919769?q=80&w=1974&auto=format&fit=crop', '2025-09-25 19:30:00', NULL, 'Auditório da Universidade', 75.00, 250, 3),
  ('Show de Mágica e Ilusionismo', 'Um espetáculo para toda a família com o Mágico Internacional.', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fmidias.jornalcruzeiro.com.br%2Fwp-content%2Fuploads%2F2020%2F07%2FO-show-de-m%25C3%25A1gica-tamb%25C3%25A9m-0.jpg&f=1&nofb=1&ipt=764565662f23414a82a4420eeb3db172d2f4a99a09f9295fbf3d3a037c52592f', '2025-08-30 17:00:00', '2025-08-30 19:00:00', 'Teatro Mágico', 90.00, 400, 1),
  ('Campeonato de Skate Amador', 'Manobras radicais e muita adrenalina na pista de skate da cidade.', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fimg.redbull.com%2Fimages%2Fc_crop%2Cw_3840%2Ch_1920%2Cx_0%2Cy_0%2Cf_auto%2Cq_auto%2Fc_scale%2Cw_1200%2Fredbullcom%2F2023%2F12%2F11%2Fgequ9mc9karvw3pqj0qc%2Fskate-ryan-decenzo-mundial-street-2021&f=1&nofb=1&ipt=453ac3b4cc6a1a286c933fd62bc5500be644e0649a71e6aaff455eeade4d00d9', '2025-10-04 10:00:00', '2025-10-04 18:00:00', 'Skate Park Municipal', 15.00, 100, 2),
  ('Festival de Jazz & Blues', 'Dois dias de música de alta qualidade ao ar livre.', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fimages.rove.me%2Fw_1920%2Cq_85%2Fxb3lpwf4lhwky1i296jo%2Fmontreal-festival-international-de-jazz-de-montreal.jpg&f=1&nofb=1&ipt=ceab3026a7d04785bec6e5d82c75c43e9acb82c008738812a3c36a6d89d6ad7e', '2025-11-21 18:00:00', '2025-11-22 23:59:00', 'Parque das Nações', 100.00, 600, 3),
  ('Feira de Adoção de Animais', 'Encontre seu novo amigo de quatro patas. Entrada gratuita.', NULL, '2025-08-17 10:00:00', '2025-08-17 16:00:00', 'Praça Central', 0.00, 1000, 1),
  ('Bazar de Roupas Vintage', 'Garimpe peças únicas e com história.', 'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?q=80&w=2070&auto=format&fit=crop', '2025-09-20 09:00:00', '2025-09-21 17:00:00', 'Armazém Cultural', 5.00, 300, 2),
  ('Aula de Culinária Italiana', 'Aprenda a fazer massas frescas e molhos clássicos.', NULL, '2025-10-25 19:00:00', '2025-10-25 22:00:00', 'Espaço Gourmet', 250.00, 20, 3),
  ('Peça Teatral: "O Auto da Compadecida"', 'A clássica obra de Ariano Suassuna nos palcos.', 'https://images.unsplash.com/photo-1503095396549-807759245b35?q=80&w=2070&auto=format&fit=crop', '2025-11-14 20:00:00', '2025-11-14 22:30:00', 'Teatro Municipal', 70.00, 350, 1),
  ('Festa Eletrônica Sunset Party', 'DJs renomados tocando o melhor da música eletrônica ao pôr do sol.', 'https://images.unsplash.com/photo-1516223725307-6f76b9ec8742?q=80&w=1994&auto=format&fit=crop', '2025-09-27 16:00:00', '2025-09-28 01:00:00', 'Clube de Campo', 180.00, 700, 2);

-- Insert tickets
INSERT INTO tickets (status, client_id, event_id) 
VALUES
  ('purchased', 4, 1),
  ('purchased', 5, 1),
  ('purchased', 6, 2),
  ('purchased', 7, 3), 
  ('purchased', 8, 3), 
  ('purchased', 9, 4), 
  ('purchased', 10, 4),
  ('purchased', 4, 5), 
  ('purchased', 5, 6), 
  ('purchased', 6, 7), 
  ('purchased', 7, 7), 
  ('purchased', 8, 8), 
  ('purchased', 9, 9), 
  ('purchased', 10, 10),
  ('purchased', 4, 11),
  ('purchased', 5, 12),
  ('purchased', 6, 13),
  ('purchased', 7, 13),
  ('purchased', 8, 13),
  ('purchased', 9, 14),
  ('purchased', 10, 15),
  ('purchased', 4, 17),
  ('purchased', 5, 18),
  ('purchased', 6, 19),
  ('purchased', 7, 20),
  ('purchased', 8, 20),
  ('purchased', 9, 1), 
  ('purchased', 10, 2);
