import { useContext, useEffect, useState } from "react"
import AuthContext from "../../contexts/AuthContext"

export default function HomeAlunosPage() {
    const { user } = useContext(AuthContext);
    const [dataHora, setDataHora] = useState(new Date());

    const formatarData = (data: any) => {
        const dia = String(data.getDate()).padStart(2, '0');
        const mes = String(data.getMonth() + 1).padStart(2, '0'); // Mês começa do 0
        const ano = data.getFullYear();
        const meses = {
            '01': 'Janeiro',
            '02': 'Fevereiro',
            '03': 'Março',
            '04': 'Abril',
            '05': 'Maio',
            '06': 'Junho',
            '07': 'Julho',
            '08': 'Agosto',
            '09': 'Setembro',
            '10': 'Outubro',
            '11': 'Novembro',
            '12': 'Dezembro'
        }
        return `${dia} de ${meses[mes]} de ${ano}`;
      };
    
      const formatarHora = (data: any) => {
        const horas = String(data.getHours()).padStart(2, '0');
        const minutos = String(data.getMinutes()).padStart(2, '0');
        const segundos = String(data.getSeconds()).padStart(2, '0');
        return `${horas}:${minutos}:${segundos}`;
      };


    useEffect(() => {
        const timer = setInterval(() => {
          setDataHora(new Date());
        }, 1000);
    
        return () => clearInterval(timer); // Cleanup do intervalo
      }, []);

    return (
        <div className="mt-3">
            <h3>Painel</h3>
            <h3>
                Olá, <strong> {user.name}</strong>
            </h3>
            <h5>
                <strong>
                    {formatarData(dataHora)}
                </strong>
            </h5>
            <hr />
            <div className="row mb-3">
                <div className="col">
                    <div className="card card-body">
                        <div className="d-flex justify-content-end">
                            <i className="bi bi-bar-chart-steps"></i>
                        </div>
                        <h4>Modalidades</h4>
                        <small>Veja todas as modalidades.</small>
                    </div>
                </div>
                <div className="col">
                    <div className="card card-body">
                        <div className="d-flex justify-content-end">
                            <i className="bi bi-bicycle"></i>
                        </div>
                        <h4>Treinos</h4>
                        <small>Veja todos os treinos.</small>
                    </div>
                </div>
            </div>

            <div className="row  mb-3">
                <div className="col">
                    <div className="card card-body">
                        <div className="d-flex justify-content-end">
                            <i className="bi bi-calendar-check"></i>
                        </div>
                        <h4>Eventos</h4>
                        <small>Veja todos os eventos.</small>
                    </div>
                </div>
                <div className="col">
                    <div className="card card-body">
                        <div className="d-flex justify-content-end">
                            <i className="bi bi-bag-heart"></i>
                        </div>
                        <h4>Promoções</h4>
                        <small>Veja todas as promoções</small>
                    </div>
                </div>
            </div>
            
        </div>
    )
}