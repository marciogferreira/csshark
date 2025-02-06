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
        {Object.keys(treino).map((name: any) => {
          const quantidade = treino && treino[name].filter((item: any) => item.show);
          
          if(quantidade.length > 0) {
            return (
              <>
                <Card>
                  <h4 className="">{name.toUpperCase()}</h4>
                </Card>

                {treino && treino[name].filter((item: any) => item.show).map((item: any, index: number) => (
                  <div className="card-treino"  key={index}>
                    <div style={{ width: '40%' }}>
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

                {/* {treino && treino[name].filter((item: any) => item.show).map((item: any, index: number) => (
                  <div className="flex" style={{ 
                      marginTop: '10px', 
                      marginBottom: '10px',
                      height: '85px', 
                      backgroundColor: '#F7F7F7' ,
                      padding: '5px'

                      }}  key={index}>
                    <div style={{ 
                        width: '25px', 
                        height: '85px', 
                        backgroundColor: 'blue', 
                        borderRadius: '10px 0px 0px 10px', 
                        color: 'white', 
                        display: 'flex', 
                        justifyContent: 'center', 
                        alignItems: 'center', 
                        padding: '5px' 
                      }}>
                      <p style={{  transform: 'rotate(270deg)', lineHeight: 0 }}>
                        {name.toUpperCase()}
                      </p>
                    </div>

                    <div style={{ width: '60%', display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
                      <strong>
                        <p className="titulo-exercicio">{item.exercicio}</p>
                      </strong>
                    </div>
                    <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
                      <div className="">
                          <strong>Series: </strong>
                          <span className="" style={{ fontSize: '1.5em' }}>
                            {item.series}
                          </span>
                        <br />
                          <strong>Reps: </strong>
                          <span className="" style={{ fontSize: '1.5em' }}>
                            {item.reps}
                          </span>
                      </div>
                      <small>{item.obs}</small>              
                    </div>
                  </div>
                ))} */}
              </>
            )
          }
        })}  
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
