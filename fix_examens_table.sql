-- Script pour corriger la table examens
ALTER TABLE examens DROP FOREIGN KEY examens_dossiers_medicaux_id_foreign;
ALTER TABLE examens MODIFY dossiers_medicaux_id BIGINT UNSIGNED NULL;
ALTER TABLE examens ADD CONSTRAINT examens_dossiers_medicaux_id_foreign FOREIGN KEY (dossiers_medicaux_id) REFERENCES dossiers__medicauxes(id) ON DELETE SET NULL;
