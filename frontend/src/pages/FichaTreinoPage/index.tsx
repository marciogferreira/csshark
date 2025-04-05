import Api from "../../core/api";
import lista_treinos from './lista_treinos';
import { useContext, useEffect, useState } from "react";
import AuthContext from "../../contexts/AuthContext";

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
              .map((value: any, index: number) => (
              <>
                <li style={{ listStyle: 'none' }} className={`rounded-md mt-3 p-4   ${index % 2 == 0 ? 'bg-blue-950 text-white' : 'bg-gray-300 text-black-200'} `} onClick={() => setSelectedType(value)}>
                  <h4>Treino {value}</h4>
                </li>
              </>
            ))}
          </>
        }
        {selectedType !== null && 
          <>
            <h3 className="aba-left">Treino::Tipo {selectedType}</h3>
            
            {Object.keys(treino).filter((name: any) => {
              return name;
              return treino.tipos[name] === selectedType;
            }).map((name: any) => (
              <>
                {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === selectedType).length > 0 &&
                  <>
                    <br /><br />
                    <h4 className="">{name.toUpperCase()}</h4>
                    <hr style={{ border: '2px solid blue' }} />
                  </>
                }
                {/* {treino && name != 'tipos' && treino[name].filter((item: any) => item.show).length <= 0 && <h5 className="text-center p-2">Sem Sequências Definidas - Fale com seu(ua) Personal</h5>} */}
                {treino && name != 'tipos' && treino[name]
                .filter((item: any) => item.show && item.tipo === selectedType)
                .map((item: any, index: number) => (
                  <div className="card-treino"  key={index}>
                    <div className="text-left"  style={{ width: '40%' }}>
                        <h4 className="titulo-exercicio">
                          {
                            [
                              "Complementar 01", 
                              "Complementar 02",
                              "Complementar 03",
                              "Complementar 04",
                              "Complementar 05",
                              "Complementar 06",
                              "Complementar 07",
                              "Complementar 08",
                              "Complementar 09",
                              "Complementar 10"
                            ]
                            .includes(item.exercicio) ? item.obs : item.exercicio}

                        </h4>
                        <h4 style={{ paddingLeft: 10, color: '#777' }}>
                        {
                            [
                              "Complementar 01", 
                              "Complementar 02",
                              "Complementar 03",
                              "Complementar 04",
                              "Complementar 05",
                              "Complementar 06",
                              "Complementar 07",
                              "Complementar 08",
                              "Complementar 09",
                              "Complementar 10"
                            ]
                            .includes(item.exercicio) ? null : item.obs
                          }
                        </h4>
                    </div>
                    <div className="" style={{ width: '60%' }}>
                      <div className="circles">
                        <span className="circle-1">{item.series}</span>
                        <span className="circle-x">X</span>
                        <span className="circle-2">{item.reps}</span>
                      </div>
                    </div>
                  </div>
                ))}
              </>
            ))} 

            {status &&
              <div className="d-flex justify-content-center mb-3 mt-3">
                <button className="btn btn-primary"  onClick={() => setSelectedType(null)}>Finalizar Treino</button>
              </div>
            }
          </> 
        }
      </>
    }
      
    </>
    )
}
