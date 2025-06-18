--
-- PostgreSQL database dump
--

-- Dumped from database version 17.5 (Debian 17.5-1.pgdg120+1)
-- Dumped by pg_dump version 17.5 (Debian 17.5-1.pgdg120+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: rooms; Type: TABLE; Schema: public; Owner: meetspace
--

CREATE TABLE public.rooms (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    capacity integer NOT NULL,
    description text,
    equipment text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    is_active boolean DEFAULT true
);


ALTER TABLE public.rooms OWNER TO meetspace;

--
-- Name: active_rooms; Type: VIEW; Schema: public; Owner: meetspace
--

CREATE VIEW public.active_rooms AS
 SELECT id,
    name,
    capacity,
    description,
    equipment,
    created_at,
    is_active
   FROM public.rooms
  WHERE (is_active = true);


ALTER VIEW public.active_rooms OWNER TO meetspace;

--
-- Name: reservations; Type: TABLE; Schema: public; Owner: meetspace
--

CREATE TABLE public.reservations (
    id integer NOT NULL,
    room_id integer,
    user_id integer,
    start_time timestamp without time zone NOT NULL,
    end_time timestamp without time zone NOT NULL,
    purpose text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    status character varying(20) DEFAULT 'pending'::character varying
);


ALTER TABLE public.reservations OWNER TO meetspace;

--
-- Name: users; Type: TABLE; Schema: public; Owner: meetspace
--

CREATE TABLE public.users (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    role character varying(50) DEFAULT 'user'::character varying NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.users OWNER TO meetspace;

--
-- Name: current_reservations; Type: VIEW; Schema: public; Owner: meetspace
--

CREATE VIEW public.current_reservations AS
 SELECT r.id,
    r.start_time,
    r.end_time,
    r.purpose,
    u.name AS user_name,
    rm.name AS room_name
   FROM ((public.reservations r
     JOIN public.users u ON ((r.user_id = u.id)))
     JOIN public.rooms rm ON ((r.room_id = rm.id)))
  WHERE ((CURRENT_TIMESTAMP >= r.start_time) AND (CURRENT_TIMESTAMP <= r.end_time));


ALTER VIEW public.current_reservations OWNER TO meetspace;

--
-- Name: reservation_details; Type: VIEW; Schema: public; Owner: meetspace
--

CREATE VIEW public.reservation_details AS
 SELECT r.id,
    r.start_time,
    r.end_time,
    r.purpose,
    r.created_at,
    u.name AS user_name,
    u.email AS user_email,
    rm.name AS room_name,
    rm.capacity AS room_capacity,
    rm.equipment AS room_equipment
   FROM ((public.reservations r
     JOIN public.users u ON ((r.user_id = u.id)))
     JOIN public.rooms rm ON ((r.room_id = rm.id)))
  ORDER BY r.start_time DESC;


ALTER VIEW public.reservation_details OWNER TO meetspace;

--
-- Name: reservations_id_seq; Type: SEQUENCE; Schema: public; Owner: meetspace
--

CREATE SEQUENCE public.reservations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reservations_id_seq OWNER TO meetspace;

--
-- Name: reservations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: meetspace
--

ALTER SEQUENCE public.reservations_id_seq OWNED BY public.reservations.id;


--
-- Name: rooms_id_seq; Type: SEQUENCE; Schema: public; Owner: meetspace
--

CREATE SEQUENCE public.rooms_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.rooms_id_seq OWNER TO meetspace;

--
-- Name: rooms_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: meetspace
--

ALTER SEQUENCE public.rooms_id_seq OWNED BY public.rooms.id;


--
-- Name: today_reservations; Type: VIEW; Schema: public; Owner: meetspace
--

CREATE VIEW public.today_reservations AS
 SELECT r.id,
    r.start_time,
    r.end_time,
    r.purpose,
    u.name AS user_name,
    rm.name AS room_name
   FROM ((public.reservations r
     JOIN public.users u ON ((r.user_id = u.id)))
     JOIN public.rooms rm ON ((r.room_id = rm.id)))
  WHERE (date(r.start_time) = CURRENT_DATE)
  ORDER BY r.start_time;


ALTER VIEW public.today_reservations OWNER TO meetspace;

--
-- Name: user_reservations; Type: VIEW; Schema: public; Owner: meetspace
--

CREATE VIEW public.user_reservations AS
 SELECT r.id,
    r.start_time,
    r.end_time,
    r.purpose,
    r.created_at,
    rm.name AS room_name,
    rm.capacity,
    rm.equipment
   FROM (public.reservations r
     JOIN public.rooms rm ON ((r.room_id = rm.id)))
  WHERE (r.user_id = 1)
  ORDER BY r.start_time DESC;


ALTER VIEW public.user_reservations OWNER TO meetspace;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: meetspace
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO meetspace;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: meetspace
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: reservations id; Type: DEFAULT; Schema: public; Owner: meetspace
--

ALTER TABLE ONLY public.reservations ALTER COLUMN id SET DEFAULT nextval('public.reservations_id_seq'::regclass);


--
-- Name: rooms id; Type: DEFAULT; Schema: public; Owner: meetspace
--

ALTER TABLE ONLY public.rooms ALTER COLUMN id SET DEFAULT nextval('public.rooms_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: meetspace
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: reservations; Type: TABLE DATA; Schema: public; Owner: meetspace
--

COPY public.reservations (id, room_id, user_id, start_time, end_time, purpose, created_at, status) FROM stdin;
6	2	3	2025-06-18 23:24:00	2025-06-19 12:00:00	Spotkanie z klientem	2025-06-18 21:25:20.79094	pending
7	1	3	2025-06-19 07:25:00	2025-06-19 08:00:00	Daily stand-up	2025-06-18 21:25:55.847489	pending
8	2	1	2025-06-20 08:00:00	2025-06-20 12:00:00	Maintenance window	2025-06-18 21:27:47.077339	pending
9	3	1	2025-06-18 23:37:00	2025-06-18 23:40:00	Test	2025-06-18 21:36:44.351047	pending
\.


--
-- Data for Name: rooms; Type: TABLE DATA; Schema: public; Owner: meetspace
--

COPY public.rooms (id, name, capacity, description, equipment, created_at, is_active) FROM stdin;
1	Denali Conference room	5	8. pi─Ötro obok kuchni	zestaw wideokonferencyjny, telewizor, klimatyzacja	2025-06-17 21:22:25.156732	t
2	Everest Conference Room	12	7. pi─Ötro obok serwerowni	tablica, klimatyzacja, telewizor	2025-06-18 21:16:26.788498	t
3	Libi─à┼╝ Conference room	2	7. pi─Ötro na ko┼äcu korytarza	projektor, wentylator	2025-06-18 21:19:33.205561	t
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: meetspace
--

COPY public.users (id, email, password, name, role, created_at) FROM stdin;
1	admin@meetspace.com	$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi	Admin	admin	2025-06-17 20:43:48.059464
2	test@test.com	$2y$10$u1P1pSad3CcmFHPjapEJVO4kGy6.H2rbvDPxtlPT/4gSc2qfu4Kk2	test	user	2025-06-17 21:09:31.719413
3	adam@gmail.com	$2y$10$MQCBGyJC2WeVoNM8IdZUdeiTWfwgDvOU1h1Tsks1HDMfZ2vRenoa2	Adam Nowak	user	2025-06-18 21:08:50.815067
\.


--
-- Name: reservations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: meetspace
--

SELECT pg_catalog.setval('public.reservations_id_seq', 9, true);


--
-- Name: rooms_id_seq; Type: SEQUENCE SET; Schema: public; Owner: meetspace
--

SELECT pg_catalog.setval('public.rooms_id_seq', 3, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: meetspace
--

SELECT pg_catalog.setval('public.users_id_seq', 3, true);


--
-- Name: reservations reservations_pkey; Type: CONSTRAINT; Schema: public; Owner: meetspace
--

ALTER TABLE ONLY public.reservations
    ADD CONSTRAINT reservations_pkey PRIMARY KEY (id);


--
-- Name: rooms rooms_pkey; Type: CONSTRAINT; Schema: public; Owner: meetspace
--

ALTER TABLE ONLY public.rooms
    ADD CONSTRAINT rooms_pkey PRIMARY KEY (id);


--
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: meetspace
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: meetspace
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: reservations reservations_room_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: meetspace
--

ALTER TABLE ONLY public.reservations
    ADD CONSTRAINT reservations_room_id_fkey FOREIGN KEY (room_id) REFERENCES public.rooms(id) ON DELETE CASCADE;


--
-- Name: reservations reservations_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: meetspace
--

ALTER TABLE ONLY public.reservations
    ADD CONSTRAINT reservations_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

