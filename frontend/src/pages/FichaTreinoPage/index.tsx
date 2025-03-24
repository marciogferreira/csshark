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
    const[selectedType, setSelectedType] = useState(null);
    
    
    async function getTreino() {
      try {
        setLoading(true);
        const response = await Api.get(`ficha-aluno/${user.email}`);
        setTreino({...treino, ...JSON.parse(response.data.data)});
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

  console.log(treino)

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
        {selectedType === null && 
          <>
            <h4>Selecione o Tipo de Treino</h4>
            {
              Object.values(treino.tipos)
              .map((value: any) => value)
              .reduce((acc: any, value: any) => {
                if(acc.includes(value)) return acc;
                return [...acc, value];
              })
              .sort()
              .map((value: any) => (
              <>
                <li className="mt-3 card p-2" onClick={() => setSelectedType(value)}>
                  <h4>Treino {value}</h4>
                </li>
              </>
            ))}
          </>
        }
        {selectedType !== null && 
          <>
            <div className="d-flex justify-content-end">
              <button className="btn btn-warning btn-sm" onClick={() => setSelectedType(null)}>Voltar</button>
            </div>
            <br />
            {Object.keys(treino).filter((name: any) => {
              return treino.tipos[name] === selectedType;
            }).map((name: any) => (
              <>
                <Card>
                  <h4 className="">{name.toUpperCase()}</h4>
                </Card>
                {treino && name != 'tipos' && treino[name].filter((item: any) => item.show).length <= 0 && <h5 className="text-center p-2">Sem Sequências Definidas - Fale com seu(ua) Personal</h5>}
                {treino && name != 'tipos' && treino[name].filter((item: any) => item.show).map((item: any, index: number) => (
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

            {status &&
              <div className="d-flex justify-content-center mb-3 mt-3">
                <button className="btn btn-primary">Finalizar Treino</button>
              </div>
            }
          </> 
        }
      </>
    }
      
    </>
    )
}
