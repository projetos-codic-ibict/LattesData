CREATE TABLE "dataverse_tsv_vocabulary" (
        "id_vc" SERIAL NOT NULL,
        "vc_name" VARCHAR(100) NOT NULL,
        "vc_value" VARCHAR(100) NOT NULL,
        "vc_class_1" VARCHAR(10) NOT NULL,
        "vc_order" INT DEFAULT 0 NOT NULL,
        created_at TIMESTAMP DEFAULT NOW(),
        updated_at TIMESTAMP DEFAULT NOW(),
        CONSTRAINT "pk_dataverse_tsv_vocabulary" PRIMARY KEY("id_vc"));

