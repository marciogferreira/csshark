import { useEffect, useState } from "react"
import Api from "../../core/api";
import { Card } from "react-bootstrap";

export default function ModalidadesAlunosPages() {
    const [list, setList] = useState([]);
    async function getData() {
        const response = await Api.get('modalidades', {});
        setList(response.data.data);
    }

    async function matricular(id: number) {
        console.log(id)
    }

    useEffect(() => {
        getData()
    }, [])
    return (
        <>
            <h4>Nossas Modalidades</h4>
            <hr />
            {list.map((item: any, index: number) => (
                <Card className="mb-3 p-3" key={index}>
                    <h4>{item.nome}</h4>
                    <p>{item.descricao}</p>
                    <button onClick={() => matricular(item.id)} className="btn btn-warning btn-sm">Matriculá-me</button>
                </Card>
            ))}
        </>
    )
}
