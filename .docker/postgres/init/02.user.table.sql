\connect orm;
CREATE TABLE public.user (
    id uuid NULL,
    first_name varchar(255) NOT NULL,
    last_name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    created_at timestamp NOT NULL,
    updated_at timestamp NOT NULL,
    CONSTRAINT user_pk PRIMARY KEY (id)
);
