import { useContext, useEffect, useState } from "react"
import Api from "../../core/api";
import { Card } from "react-bootstrap";
import AuthContext from "../../contexts/AuthContext";
import Message from "../../core/Message";

export default function ModalidadesAlunosPages() {
    const {  user}= useContext(AuthContext)
    const [list, setList] = useState([]);
    async function getData() {
        const response = await Api.get('turmas', {});
        setList(response.data.data);
    }

    async function matricular(id: number) {

        const dataAtual = new Date();
        const ano = dataAtual.getFullYear();
        const mes = String(dataAtual.getMonth() + 1).padStart(2, '0'); // mês começa em 0
        const dia = String(dataAtual.getDate()).padStart(2, '0');

        const dataFormatada = `${ano}-${mes}-${dia}`;

        await Api.post('alunos-turmas', {
            aluno_id: user.id,
            turma_id: id,
            observacao: 'Matricula inicial',
            data_inicio: dataFormatada,
            valor: 0,
            desconto: 0,
        })
        Message.success("Matrícula Realizada com Sucesso. Compareça na Recepção para Confirmar sua Matrícula e Participar das aulas.")
        getData();
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
                    <img src="https://cdn-icons-png.flaticon.com/512/5073/5073650.png" className="rounded mx-auto d-block" alt="..." />
                    <h4>{item.nome}</h4>
                    <div className="">
                    <div>
                        <strong>Turno: </strong>{item.turno} <br />
                    </div>
                    <div>
                        <strong>Valor: </strong>R$ {item.valor} <br />
                    </div>
                    {item.matriculado && 
                        <div>
                            <strong>Data da Matrícula: </strong>{item.matriculado.data_inicio} <br />
                        </div>
                    }
                    </div>
                    <p>{item.descricao}</p>

                    {}
                    {item.matriculado && <h3 className="alert alert-primary">Matriculado</h3> }
                    {!item.matriculado &&
                        <button onClick={() => matricular(item.id)} className={`btn btn-${item.matriculado ? 'primary' : 'warning'} btn-sm`}>{item.matriculado ? 'Matriculado' : 'Matricule-se'}</button>
                    }
                </Card>
            ))}
        </>
    )
}
