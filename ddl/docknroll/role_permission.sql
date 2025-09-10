create table if not exists role_permission
(
    roleid       int not null,
    permissionid int not null,
    primary key (roleid, permissionid)
);

