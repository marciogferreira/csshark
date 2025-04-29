import Api from "../../core/api";
import lista_treinos from './lista_treinos';
import { useContext, useEffect, useRef, useState } from "react";
import AuthContext from "../../contexts/AuthContext";
import metidando from '../../assets/metidando.gif'
import { Button } from "react-bootstrap";
type Treino = {
  [key: string]: any; // As propriedades podem ser string ou number
};

interface AlunoData {
    nome: ''
}

export default function Print({ aluno_id }: any) {
    
    const { setLoading } = useContext(AuthContext);
    
    const divRef = useRef<HTMLDivElement>(null);
    const[treino, setTreino] = useState<Treino>(lista_treinos);
    const[status, setStatus] = useState('');
    const[aluno, setAluno] = useState<AlunoData>({ nome: '' });

    async function getTreino() {
      try {
        setLoading(true);
        const response = await Api.get(`ficha-aluno-id/${aluno_id}`);
        setTreino({...treino, ...JSON.parse(response.data.data)});
        
        if(response.data.data) {
            setAluno(response.data.aluno);
            setStatus('successo');
        }
      } catch(e) {
        
      } finally {
        setLoading(false);
      }
        
    }

    function imprimir() {
        
        const divConteudo = divRef.current?.innerHTML;
        const win = window.open();
        if(win) {
            win.document.write('<html><head><title>Imprimir</title></head><body>' + divConteudo + '</body></html>');
            win.document.close();
            win.print();
            win.close();
        }
    }

    useEffect(() => {
        getTreino()
    }, [])

    return (
        <div style={{ display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center' }}>
            <Button variant="primary" size="sm" onClick={imprimir}>
                Imprimir
            </Button>
            <div ref={divRef} id="print" style={{ width: '200px', padding: '10px', background: '#f7f4c0' }}>
            {!status ? 
                <div className="d-flex justify-content-center flex-column">
                    <img src={metidando} alt="" />
                    <p className="text-center">Aguarde seu <strong>Personal</strong> cadastrar seu Treino.</p>
                    <button onClick={() => getTreino()} className="btn btn-warning btn-sm">Atualizar Treino</button>
                </div>
            :
            <>  
                <strong><p style={{ textAlign: "center" }}>CShark</p></strong>
                <p style={{ fontSize: '10px' }}>
                    <strong>Aluno: </strong>
                    <br /> {aluno.nome || ''}
                </p>
                <small style={{ fontSize: '10px' }}>

                    <p style={{ textAlign: "center", marginTop: '20px' }}>Treino::Tipo A</p>

                    {Object.keys(treino).filter((name: any) => {
                    return name;
                    }).map((name: any) => (
                    <>
                        {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'A').length > 0 &&
                        <>
                            <p style={{ textAlign: 'center', marginTop: '12px', borderBottom: '1px solid black' }}>
                                <strong>{name.toUpperCase()}</strong>
                            </p>
                        </>
                        }
                        {/* {treino && name != 'tipos' && treino[name].filter((item: any) => item.show).length <= 0 && <h5 className="text-center p-2">Sem Sequências Definidas - Fale com seu(ua) Personal</h5>} */}
                        {treino && name != 'tipos' && treino[name]
                        .filter((item: any) => item.show && item.tipo === 'A')
                        .map((item: any, index: number) => (
                        <div style={{ display: 'flex', padding: 4, borderBottom: '1px solid #ccc' }}  key={index}>
                            <div className="text-left" style={{ width: '70%' }}>
                                <strong className="">
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
                                </strong>
                                <strong style={{ }}>
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
                                </strong>
                            </div>
                            <div className="" style={{ width: '30%', textAlign: "right" }}>
                            <div className="">
                                <span className="">{item.series}</span>
                                <span className="">X</span>
                                <span className="">{item.reps}</span>
                            </div>
                            </div>
                        </div>
                        ))}
                    </>
                    ))} 

                </small> 

                {/* B */}
                <small style={{ fontSize: '10px' }}>
                    {Object.keys(treino).filter((name: any) => {
                        return name;
                    }).map((name: any) => (
                        <>
                            {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'B').length > 0 &&
                                <p style={{ textAlign: "center", marginTop: '20px' }}>Treino::Tipo B</p>
                            }
                        </>
                    ))}

                    {Object.keys(treino).filter((name: any) => {
                    return name;
                    }).map((name: any) => (
                    <>
                        {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'B').length > 0 &&
                        <>
                            <p style={{ textAlign: 'center', marginTop: '12px', borderBottom: '1px solid black' }}>
                                <strong>{name.toUpperCase()}</strong>
                            </p>
                        </>
                        }
                        {treino && name != 'tipos' && treino[name]
                        .filter((item: any) => item.show && item.tipo === 'B')
                        .map((item: any, index: number) => (
                        <div style={{ display: 'flex', padding: 4, borderBottom: '1px solid #ccc' }}  key={index}>
                            <div className="text-left" style={{ width: '70%' }}>
                                <strong className="">
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
                                </strong>
                                <strong style={{ }}>
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
                                </strong>
                            </div>
                            <div className="" style={{ width: '30%', textAlign: "right" }}>
                                <div className="">
                                    <span className="">{item.series}</span>
                                    <span className="">X</span>
                                    <span className="">{item.reps}</span>
                                </div>
                            </div>
                        </div>
                        ))}
                    </>
                    ))} 

                </small> 

                {/* C */}
                <small style={{ fontSize: '10px' }}>
                    {Object.keys(treino).filter((name: any) => {
                        return name;
                    }).map((name: any) => (
                        <>
                            {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'C').length > 0 &&
                                <p style={{ textAlign: "center", marginTop: '20px' }}>Treino::Tipo C</p>
                            }
                        </>
                    ))}

                    {Object.keys(treino).filter((name: any) => {
                    return name;
                    }).map((name: any) => (
                    <>
                        {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'C').length > 0 &&
                        <>
                            <p style={{ textAlign: 'center', marginTop: '12px', borderBottom: '1px solid black' }}>
                                <strong>{name.toUpperCase()}</strong>
                            </p>
                        </>
                        }
                        {treino && name != 'tipos' && treino[name]
                        .filter((item: any) => item.show && item.tipo === 'C')
                        .map((item: any, index: number) => (
                        <div style={{ display: 'flex', padding: 4, borderBottom: '1px solid #ccc' }}  key={index}>
                            <div className="text-left" style={{ width: '70%' }}>
                                <strong className="">
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
                                </strong>
                                <strong style={{ }}>
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
                                </strong>
                            </div>
                            <div className="" style={{ width: '30%', textAlign: "right" }}>
                                <div className="">
                                    <span className="">{item.series}</span>
                                    <span className="">X</span>
                                    <span className="">{item.reps}</span>
                                </div>
                            </div>
                        </div>
                        ))}
                    </>
                    ))} 

                </small> 

                {/* D */}
                <small style={{ fontSize: '10px' }}>
                    
                    {Object.keys(treino).filter((name: any) => {
                        return name;
                    }).map((name: any) => (
                        <>
                            {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'D').length > 0 &&
                                <p style={{ textAlign: "center", marginTop: '20px' }}>Treino::Tipo D</p>
                            }
                        </>
                    ))}

                    {Object.keys(treino).filter((name: any) => {
                    return name;
                    }).map((name: any) => (
                    <>
                        {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'D').length > 0 &&
                        <>
                            <p style={{ textAlign: 'center', marginTop: '12px', borderBottom: '1px solid black' }}>
                                <strong>{name.toUpperCase()}</strong>
                            </p>
                        </>
                        }
                        {treino && name != 'tipos' && treino[name]
                        .filter((item: any) => item.show && item.tipo === 'D')
                        .map((item: any, index: number) => (
                        <div style={{ display: 'flex', padding: 4, borderBottom: '1px solid #ccc' }}  key={index}>
                            <div className="text-left" style={{ width: '70%' }}>
                                <strong className="">
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
                                </strong>
                                <strong style={{ }}>
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
                                </strong>
                            </div>
                            <div className="" style={{ width: '30%', textAlign: "right" }}>
                                <div className="">
                                    <span className="">{item.series}</span>
                                    <span className="">X</span>
                                    <span className="">{item.reps}</span>
                                </div>
                            </div>
                        </div>
                        ))}
                    </>
                    ))} 

                </small>

                {/* E */}
                <small style={{ fontSize: '10px' }}>
                    

                    {Object.keys(treino).filter((name: any) => {
                        return name;
                    }).map((name: any) => (
                        <>
                            {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'E').length > 0 &&
                                <p style={{ textAlign: "center", marginTop: '20px' }}>Treino::Tipo E</p>
                            }
                        </>
                    ))}

                    {Object.keys(treino).filter((name: any) => {
                    return name;
                    }).map((name: any) => (
                    <>
                        {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'E').length > 0 &&
                        <>
                            <p style={{ textAlign: 'center', marginTop: '12px', borderBottom: '1px solid black' }}>
                                <strong>{name.toUpperCase()}</strong>
                            </p>
                        </>
                        }
                        {treino && name != 'tipos' && treino[name]
                        .filter((item: any) => item.show && item.tipo === 'E')
                        .map((item: any, index: number) => (
                        <div style={{ display: 'flex', padding: 4, borderBottom: '1px solid #ccc' }}  key={index}>
                            <div className="text-left" style={{ width: '70%' }}>
                                <strong className="">
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
                                </strong>
                                <strong style={{ }}>
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
                                </strong>
                            </div>
                            <div className="" style={{ width: '30%', textAlign: "right" }}>
                                <div className="">
                                    <span className="">{item.series}</span>
                                    <span className="">X</span>
                                    <span className="">{item.reps}</span>
                                </div>
                            </div>
                        </div>
                        ))}
                    </>
                    ))} 

                </small>

                {/* E */}
                <small style={{ fontSize: '10px' }}>
                    {Object.keys(treino).filter((name: any) => {
                        return name;
                    }).map((name: any) => (
                        <>
                            {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'F').length > 0 &&
                                <p style={{ textAlign: "center", marginTop: '20px' }}>Treino::Tipo F</p>
                            }
                        </>
                    ))}
                    {Object.keys(treino).filter((name: any) => {
                    return name;
                    }).map((name: any) => (
                    <>
                        {treino && name != 'tipos' && treino[name].filter((item: any) => item.show && item.tipo === 'F').length > 0 &&
                        <>
                            <p style={{ textAlign: 'center', marginTop: '12px', borderBottom: '1px solid black' }}>
                                <strong>{name.toUpperCase()}</strong>
                            </p>
                        </>
                        }
                        {treino && name != 'tipos' && treino[name]
                        .filter((item: any) => item.show && item.tipo === 'F')
                        .map((item: any, index: number) => (
                        <div style={{ display: 'flex', padding: 4, borderBottom: '1px solid #ccc' }}  key={index}>
                            <div className="text-left" style={{ width: '70%' }}>
                                <strong className="">
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
                                </strong>
                                <strong style={{ }}>
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
                                </strong>
                            </div>
                            <div className="" style={{ width: '30%', textAlign: "right" }}>
                                <div className="">
                                    <span className="">{item.series}</span>
                                    <span className="">X</span>
                                    <span className="">{item.reps}</span>
                                </div>
                            </div>
                        </div>
                        ))}
                    </>
                    ))} 

                </small>
                
            </>
            }
            
            </div>
            <Button variant="primary" size="sm" onClick={imprimir}>
                Imprimir
            </Button>
        </div>
    )
}
