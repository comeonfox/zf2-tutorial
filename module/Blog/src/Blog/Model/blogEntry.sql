create table blog_entry(
    id  bigint(20) not null auto_increment,
    title text not null,
    author varchar(20) not null,
    time_created timestamp default current_timestamp,
    time_altered timestamp,
    body longtext not null,
    comment_count bigint(20),

    primary key (id)
);

