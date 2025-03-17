Schema della Tabella corsi
ID (Intero, chiave primaria)

Identificatore unico per ogni corso.
Nome (Stringa)

Nome del corso (es. "Yoga", "Pilates", "Bodybuilding").
Descrizione (Testo)

Una breve descrizione del corso che può includere obiettivi, benefici, e dettagli generali.
Categoria (Stringa)

Categoria del corso, utile per raggruppare corsi simili (es. "Allenamento", "Benessere", "Cardio").
Durata (Intero)

Durata del corso in minuti o ore (es. "60" per un'ora).
Frequenza (Stringa)

Frequenza del corso (es. "Settimanale", "Giornaliero").
Orario (Stringa)

Orario in cui il corso si svolge (es. "18:00 - 19:00").
Prezzo (Decimale)

Prezzo per partecipare al corso (opzionale, se applicabile).
Max Partecipanti (Intero)

Numero massimo di partecipanti per il corso.
Trainer ID (Intero, chiave esterna)

ID del trainer che tiene il corso, in relazione alla tabella dei trainer.
Data Inizio (Data)

Data di inizio del corso.
Data Fine (Data)

Data di fine del corso (se il corso è a termine fisso).
Stato (Stringa)

Stato del corso (es. "Attivo", "Inattivo").
Luogo (Stringa)

Luogo o sala dove si svolge il corso, se applicabile.
Livello (Stringa)

Livello di difficoltà del corso (es. "Principiante", "Intermedio", "Avanzato").
Immagine (Stringa)

URL o percorso dell'immagine del corso (opzionale).
