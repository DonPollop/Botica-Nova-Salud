PGDMP  4                    }            botica_nova_salud    17.4    17.4     �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            �           1262    24636    botica_nova_salud    DATABASE     w   CREATE DATABASE botica_nova_salud WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'es-MX';
 !   DROP DATABASE botica_nova_salud;
                     postgres    false            �            1259    24647 	   productos    TABLE     5  CREATE TABLE public.productos (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    description text,
    stock integer DEFAULT 0 NOT NULL,
    precio numeric(10,2) NOT NULL,
    min_stock integer DEFAULT 5 NOT NULL,
    fecha_regis timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
    DROP TABLE public.productos;
       public         heap r       postgres    false            �            1259    24646    productos_id_seq    SEQUENCE     �   CREATE SEQUENCE public.productos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.productos_id_seq;
       public               postgres    false    220            �           0    0    productos_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.productos_id_seq OWNED BY public.productos.id;
          public               postgres    false    219            �            1259    24638    users    TABLE     �   CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    password character varying(255) NOT NULL
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    24637    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    218            �           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    217            �            1259    32810    venta    TABLE     �   CREATE TABLE public.venta (
    id integer NOT NULL,
    product_id integer NOT NULL,
    items_vendidos integer NOT NULL,
    total numeric(10,2) NOT NULL,
    date timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
    DROP TABLE public.venta;
       public         heap r       postgres    false            �            1259    32809    venta_id_seq    SEQUENCE     �   CREATE SEQUENCE public.venta_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.venta_id_seq;
       public               postgres    false    222            �           0    0    venta_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.venta_id_seq OWNED BY public.venta.id;
          public               postgres    false    221            ,           2604    24650    productos id    DEFAULT     l   ALTER TABLE ONLY public.productos ALTER COLUMN id SET DEFAULT nextval('public.productos_id_seq'::regclass);
 ;   ALTER TABLE public.productos ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    219    220    220            +           2604    24641    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    217    218    218            0           2604    32813    venta id    DEFAULT     d   ALTER TABLE ONLY public.venta ALTER COLUMN id SET DEFAULT nextval('public.venta_id_seq'::regclass);
 7   ALTER TABLE public.venta ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    221    222    222            �          0    24647 	   productos 
   TABLE DATA           a   COPY public.productos (id, name, description, stock, precio, min_stock, fecha_regis) FROM stdin;
    public               postgres    false    220   L       �          0    24638    users 
   TABLE DATA           7   COPY public.users (id, username, password) FROM stdin;
    public               postgres    false    218          �          0    32810    venta 
   TABLE DATA           L   COPY public.venta (id, product_id, items_vendidos, total, date) FROM stdin;
    public               postgres    false    222   r       �           0    0    productos_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.productos_id_seq', 4, true);
          public               postgres    false    219            �           0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 1, true);
          public               postgres    false    217            �           0    0    venta_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.venta_id_seq', 1, true);
          public               postgres    false    221            7           2606    24657    productos productos_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.productos
    ADD CONSTRAINT productos_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.productos DROP CONSTRAINT productos_pkey;
       public                 postgres    false    220            3           2606    24643    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    218            5           2606    24645    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    218            9           2606    32816    venta venta_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.venta
    ADD CONSTRAINT venta_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.venta DROP CONSTRAINT venta_pkey;
       public                 postgres    false    222            :           2606    32817    venta venta_product_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.venta
    ADD CONSTRAINT venta_product_id_fkey FOREIGN KEY (product_id) REFERENCES public.productos(id);
 E   ALTER TABLE ONLY public.venta DROP CONSTRAINT venta_product_id_fkey;
       public               postgres    false    222    4663    220            �   �   x�}�A
�0�����e�-�u������$�#�\{�^�ĥ0�|���\�"j�f�̼\=ʒXTI�-�$<9���A#4v�e
ug����mkt��L�F�)�W����׍GQOE�M䴾r���.��C�<xd������MQ�S��ptcNZ����u/uUU��K~      �   R   x�3�LL����T1�T14P�,)wq��-�
�w���+�L��-�su5t�M�*	�MN�6Hq���2K	�.������ cd�      �   2   x�3�4�4�42�35�4202�5 "3#c+#3+S3=KsC�=... �b�     