create table if not exists address
(
    id                int auto_increment,
    number            int         null,
    roadtype          varchar(20) null,
    roadname          varchar(60) null,
    additionaladdress varchar(60) null,
    postalcode        varchar(6)  null,
    city_id           int         null,
    numberaddition    varchar(10) null,
    constraint address_id_uindex
        unique (id)
);

create index if not exists address_city_id_fk
    on address (city_id);

alter table address
    add primary key (id);

create table if not exists city
(
    id      int auto_increment,
    inseeid varchar(6)           null,
    name    varchar(40)          not null,
    active  tinyint(1) default 1 not null,
    constraint City_id_uindex
        unique (id),
    constraint City_inseeid_uindex
        unique (inseeid)
);

alter table city
    add primary key (id);

create table if not exists message_admin
(
    id     int auto_increment,
    text   text        null,
    email  varchar(50) not null,
    isread tinyint(1)  null,
    constraint MessageAdmin_id_uindex
        unique (id)
);

alter table message_admin
    add primary key (id);

create table if not exists offer
(
    id          int auto_increment,
    name        varchar(30)          null,
    description text                 null,
    active      tinyint(1) default 1 not null,
    start       timestamp            null,
    end         timestamp            null,
    discount    int        default 0 not null,
    constraint offer_id_uindex
        unique (id)
);

alter table offer
    add primary key (id);

create table if not exists person
(
    id                    int auto_increment,
    name                  varchar(30)          null,
    firstName             varchar(30)          null,
    birthday              date                 null,
    email                 varchar(40)          not null,
    password              char(60)             null,
    photopath             varchar(255)         null,
    banned                tinyint(1) default 0 not null,
    deleted               tinyint(1) default 0 not null,
    enabled               tinyint(1) default 0 not null,
    confirmation_token    varchar(32)          null,
    password_requested_at timestamp            null,
    creationdate          timestamp            null,
    rights                int        default 1 not null,
    address_id            int                  null,
    username              varchar(30)          null,
    username_canonical    varchar(30)          null,
    email_canonical       varchar(30)          null,
    salt                  varchar(255)         null,
    plain_password        varchar(40)          null,
    last_login            timestamp            null,
    roles                 longtext             null,
    constraint Person_email_uindex
        unique (email),
    constraint Person_id_uindex
        unique (id),
    constraint Person_validationtoken_uindex
        unique (confirmation_token),
    constraint person_username_uindex
        unique (username),
    constraint Person_address_id_fk
        foreign key (address_id) references address (id)
);

alter table person
    add primary key (id);

create table if not exists persons_addresses
(
    person_id  int not null,
    address_id int not null,
    primary key (person_id, address_id),
    constraint persons_addresses_address_id_fk
        foreign key (address_id) references address (id),
    constraint persons_addresses_person_id_fk
        foreign key (person_id) references person (id)
);

create table if not exists product
(
    id          int auto_increment,
    photopath   varchar(255) null,
    price       int          null,
    description text         null,
    name        varchar(30)  null,
    active      tinyint(1)   null,
    constraint Product_id_uindex
        unique (id)
);

alter table product
    add primary key (id);

create table if not exists offer_product_content
(
    offer_id   int           not null,
    product_id int           not null,
    quantity   int default 1 not null,
    id         int auto_increment
        primary key,
    constraint offer_product_content_pk
        unique (offer_id, product_id),
    constraint offer_product_content_offer_id_fk
        foreign key (offer_id) references offer (id),
    constraint offer_product_content_product_id_fk
        foreign key (product_id) references product (id)
);

create table if not exists product_type
(
    id        int auto_increment,
    name      varchar(30)          null,
    photopath varchar(255)         null,
    active    tinyint(1) default 1 not null,
    constraint ProductType_id_uindex
        unique (id)
);

alter table product_type
    add primary key (id);

create table if not exists products_types
(
    product_id      int not null,
    product_type_id int not null,
    primary key (product_id, product_type_id),
    constraint products_types_product_id_fk
        foreign key (product_id) references product (id),
    constraint products_types_product_type_id_fk
        foreign key (product_type_id) references product_type (id)
);

create table if not exists sale
(
    id         int auto_increment,
    onlinepay  tinyint(1) default 1                 not null,
    paid       tinyint(1) default 0                 not null,
    discount   int        default 0                 not null,
    date       timestamp  default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    person_id  int                                  null,
    validated  tinyint(1) default 0                 null,
    contact    varchar(60)                          null,
    recipient  varchar(30)                          null,
    address_id int                                  null,
    constraint orders_id_uindex
        unique (id),
    constraint orders_person_id_fk
        foreign key (person_id) references person (id),
    constraint sale_address_id_fk
        foreign key (address_id) references address (id)
);

alter table sale
    add primary key (id);

create table if not exists sale_offer_content
(
    sale_id         int not null,
    offer_id        int not null,
    quantity        int not null,
    pricewhenbought int not null,
    id              int auto_increment,
    constraint sale_offer_content_id_uindex
        unique (id),
    constraint sale_offer_content_pk
        unique (sale_id, offer_id),
    constraint sale_offer_content_offer_id_fk
        foreign key (offer_id) references offer (id),
    constraint sale_offer_content_sale_id_fk
        foreign key (sale_id) references sale (id)
);

alter table sale_offer_content
    add primary key (id);

create table if not exists sale_product_content
(
    sale_id         int           not null,
    product_id      int           not null,
    quantity        int default 1 not null,
    pricewhenbought int           not null,
    id              int auto_increment
        primary key,
    constraint sale_product_content_pk
        unique (sale_id, product_id),
    constraint sale_product_content_product_id_fk
        foreign key (product_id) references product (id),
    constraint sale_product_content_sale_id_fk
        foreign key (sale_id) references sale (id)
);

create table if not exists service
(
    id          int auto_increment,
    photopath   varchar(255) null,
    price       int          null,
    description text         null,
    name        varchar(30)  null,
    active      tinyint(1)   null,
    constraint Service_id_uindex
        unique (id)
);

alter table service
    add primary key (id);

create table if not exists sale_service_content
(
    sale_id         int           not null,
    service_id      int           not null,
    quantity        int default 0 null,
    pricewhenbought int           null,
    id              int auto_increment,
    constraint sale_service_content_id_uindex
        unique (id),
    constraint sale_service_content_pk
        unique (sale_id, service_id),
    constraint sale_service_content_sale_id_fk
        foreign key (sale_id) references sale (id),
    constraint sale_service_content_service_id_fk
        foreign key (service_id) references service (id)
);

alter table sale_service_content
    add primary key (id);

create table if not exists service_product_content
(
    service_id int           not null,
    product_id int           not null,
    quantity   int default 1 not null,
    id         int auto_increment,
    constraint service_product_content_id_uindex
        unique (id),
    constraint service_product_content_pk
        unique (service_id, product_id),
    constraint service_product_content_product_id_fk
        foreign key (product_id) references product (id),
    constraint service_product_content_service_id_fk
        foreign key (service_id) references service (id)
);

alter table service_product_content
    add primary key (id);

