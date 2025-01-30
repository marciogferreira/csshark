import Api from "../../core/api";
import lista_treinos from './lista_treinos';
import { useContext, useEffect, useState } from "react";
import AuthContext from "../../contexts/AuthContext";
import Card from "../../components/Card";
import Profile from "../../components/Profile";
import metidando from '../../assets/metidando.gif'
type Treino = {
  [key: string]: any; // As propriedades podem ser string ou number
};

export default function FichaTreinoPage() {
    
    const { user, setLoading } = useContext(AuthContext);
    const[treino, setTreino] = useState<Treino>(lista_treinos);
    const[status, setStatus] = useState('');
    
    async function getTreino() {
      try {
        setLoading(true);
        const response = await Api.get(`ficha-aluno/${user.email}`);
        setTreino(JSON.parse(response.data.data));
        if(response.data.data) {
          setStatus('successo');
        }
        
      } catch(e) {
        
      } finally {
        setLoading(false);
      }
        
    }
  useEffect(() => {
    getTreino()
  }, [])

  return (
    <>
      <Profile />
      <hr />
      {!status ? 
      <div className="d-flex justify-content-center flex-column">
        <img src={metidando} alt="" />
        <p className="text-center">Aguarde seu <strong>Personal</strong> cadastrar seu Treino.</p>
        <button onClick={() => getTreino()} className="btn btn-warning btn-sm">Atualizar Treino</button>
      </div>
       :
      <>
        {Object.keys(treino).map((name: any) => (
          <>
            <Card>
              <h4 className="">{name.toUpperCase()}</h4>
            </Card>
            {treino && treino[name].filter((item: any) => item.show).map((item: any, index: number) => (
              <div className="card-treino"  key={index}>
                <div>
                  <strong>
                    <p className="titulo-exercicio">{item.exercicio}</p>
                  </strong>
                </div>
                <div>
                  <div className="circles">
                    <span className="circle-1">{item.series}</span>
                    <span className="circle-x">X</span>
                    <span className="circle-2">{item.reps}</span>
                  </div>
                  <small>{item.obs}</small>              
                </div>
              </div>
            ))}
          </>
        ))}  
      </>
    }
      {status &&
        <div className="d-flex justify-content-center mb-3 mt-3">
          <button className="btn btn-primary">Finalizar Treino</button>
        </div>
      }
    </>
    )
}
