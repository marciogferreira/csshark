const treinos = {
    peito: [
        { id: 1, exercicio: "Crucifixo c/ Halteres", series: "", reps: "", obs: "" },
        { id: 2, exercicio: "Peck Deck", series: "", reps: "", obs: "" },
        { id: 3, exercicio: "Supino Reto", series: "", reps: "", obs: "" },
        { id: 4, exercicio: "Supino Reto Maq.", series: "", reps: "", obs: "" },
        { id: 5, exercicio: "Supino Inclinado", series: "", reps: "", obs: "" },
        { id: 6, exercicio: "Supino Declinado", series: "", reps: "", obs: "" },
    ],
    costas: [
        { id: 7, exercicio: "Barra Fixa", series: "", reps: "", obs: "" },
        { id: 8, exercicio: "Puxador Alto", series: "", reps: "", obs: "" },
        { id: 9, exercicio: "Puxador Convergente", series: "", reps: "", obs: "" },
        { id: 10, exercicio: "Remada Curvada", series: "", reps: "", obs: "" },
        { id: 11, exercicio: "Remada Sentada", series: "", reps: "", obs: "" },
        { id: 12, exercicio: "Remada Unilateral", series: "", reps: "", obs: "" },
        { id: 13, exercicio: "Remada Maq.", series: "", reps: "", obs: "" },
        { id: 14, exercicio: "Lev. Terra", series: "", reps: "", obs: "" },
        { id: 15, exercicio: "Pulldown", series: "", reps: "", obs: "" },
    ],
    biceps: [
        { id: 16, exercicio: "Rosca Alternada", series: "", reps: "", obs: "" },
        { id: 17, exercicio: "Rosca Concentrada", series: "", reps: "", obs: "" },
        { id: 18, exercicio: "Rosca Direta", series: "", reps: "", obs: "" },
        { id: 19, exercicio: "Rosca Martelo", series: "", reps: "", obs: "" },
        { id: 20, exercicio: "Rosca Scott", series: "", reps: "", obs: "" },
    ],
    triceps: [
        { id: 21, exercicio: "Tríceps Francês", series: "", reps: "", obs: "" },
        { id: 22, exercicio: "Tríceps Mergulho", series: "", reps: "", obs: "" },
        { id: 23, exercicio: "Tríceps Barra Polia", series: "", reps: "", obs: "" },
        { id: 24, exercicio: "Tríceps Corda Polia", series: "", reps: "", obs: "" },
        { id: 25, exercicio: "Tríceps Testa", series: "", reps: "", obs: "" },
        { id: 26, exercicio: "Coice", series: "", reps: "", obs: "" },
    ],
    ombro: [
        { id: 27, exercicio: "Desenvolvimento", series: "", reps: "", obs: "" },
        { id: 28, exercicio: "Desenv. Arnold", series: "", reps: "", obs: "" },
        { id: 29, exercicio: "Elevação Lateral", series: "", reps: "", obs: "" },
        { id: 30, exercicio: "Elevação Lat. Maq.", series: "", reps: "", obs: "" },
        { id: 31, exercicio: "Elevação Frontal", series: "", reps: "", obs: "" },
        { id: 32, exercicio: "Remada Alta", series: "", reps: "", obs: "" },
        { id: 33, exercicio: "Encolhimento", series: "", reps: "", obs: "" },
        { id: 34, exercicio: "Crucifixo Inverso", series: "", reps: "", obs: "" },
    ],
    anterior: [
        { id: 35, exercicio: "Agachamento Livre", series: "", reps: "", obs: "" },
        { id: 36, exercicio: "Agachamento Barra", series: "", reps: "", obs: "" },
        { id: 37, exercicio: "Agachamento Guiado", series: "", reps: "", obs: "" },
        { id: 38, exercicio: "Avanço", series: "", reps: "", obs: "" },
        { id: 39, exercicio: "Cadeira Adutora", series: "", reps: "", obs: "" },
        { id: 40, exercicio: "Cadeira Extensora", series: "", reps: "", obs: "" },
        { id: 41, exercicio: "Flexão Quadril", series: "", reps: "", obs: "" },
        { id: 42, exercicio: "Hack", series: "", reps: "", obs: "" },
        { id: 43, exercicio: "Hack Pendular", series: "", reps: "", obs: "" },
        { id: 44, exercicio: "Leg Press", series: "", reps: "", obs: "" },
        { id: 45, exercicio: "Leg Press Unilateral", series: "", reps: "", obs: "" },
    ],
    posterior: [
        { id: 46, exercicio: "Agachamento Sumô", series: "", reps: "", obs: "" },
        { id: 47, exercicio: "Agach. Sumô Maq.", series: "", reps: "", obs: "" },
        { id: 48, exercicio: "Abd. de Quadril", series: "", reps: "", obs: "" },
        { id: 49, exercicio: "Cadeira Flexora", series: "", reps: "", obs: "" },
        { id: 50, exercicio: "Cadeira Abdutora", series: "", reps: "", obs: "" },
        { id: 51, exercicio: "Elevação Pélvica", series: "", reps: "", obs: "" },
        { id: 52, exercicio: "Extensão de Quadril", series: "", reps: "", obs: "" },
        { id: 53, exercicio: "Flexora em Pé", series: "", reps: "", obs: "" },
        { id: 54, exercicio: "Mesa Flexora", series: "", reps: "", obs: "" },
        { id: 55, exercicio: "Stiff", series: "", reps: "", obs: "" },
    ],
    panturrilha: [
        { id: 56, exercicio: "Panturrilha em Pé", series: "", reps: "", obs: "" },
        { id: 57, exercicio: "Panturrilha em Pé Maq.", series: "", reps: "", obs: "" },
        { id: 58, exercicio: "Panturrilha Sentada", series: "", reps: "", obs: "" },
    ],
    abdominais: [
        { id: 59, exercicio: "Infra", series: "", reps: "", obs: "" },
        { id: 60, exercicio: "Oblíquo", series: "", reps: "", obs: "" },
        { id: 61, exercicio: "Curto", series: "", reps: "", obs: "" },
        { id: 62, exercicio: "Remador", series: "", reps: "", obs: "" },
        { id: 63, exercicio: "Prancha", series: "", reps: "", obs: "" },
        { id: 64, exercicio: "Superman", series: "", reps: "", obs: "" },
    ],
    aerobico: [
        { id: 65, exercicio: "Esteira", series: "", reps: "", obs: "" },
        { id: 66, exercicio: "Bike", series: "", reps: "", obs: "" },
        { id: 67, exercicio: "Elíptico", series: "", reps: "", obs: "" },
        { id: 68, exercicio: "Aero Bike", series: "", reps: "", obs: "" },
    ]
};


export default treinos;