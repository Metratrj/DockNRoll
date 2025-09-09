create table if not exists user_roles
(
    userid  int not null,
    rolesid int not null,
    primary key (userid, rolesid),
    constraint user_roles_ibfk_1
        foreign key (userid) references users (id)
);

