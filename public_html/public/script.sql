create table apachecms_notification
(
    no_id   int auto_increment,
    ne_id   int     not null,
    us_id   int     not null,
    is_read tinyint not null,
    constraint notification_pk
        primary key (no_id)
);