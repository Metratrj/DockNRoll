create table if not exists users
(
    id         int auto_increment
        primary key,
    username   varchar(255) null,
    password   varchar(255) null,
    created_at timestamp    null
);

