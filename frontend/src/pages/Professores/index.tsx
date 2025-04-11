import danilson from '../../assets/professores/danilson.jpg'
import karyna from '../../assets/professores/karyna.jpg'
import vivi from '../../assets/professores/vivi_santos.jpg'
import bruno from '../../assets/professores/bruno_jj.jpg'
import matheus from '../../assets/professores/matheus_black_JIU JITSU.jpg'
import paulo from '../../assets/professores/paulo_anderson.jpg'
// import yuri from '../../assets/professores/yuri.jpg'
// import renam from '../../assets/professores/renam.jpg'
import leandro from '../../assets/professores/leandro.jpg'

const professores = [
    { nome: 'Danilson - LPO/Musculação', imagem: danilson },
    { nome: 'Karyna - Coach Cross', imagem: karyna },
    { nome: 'Vivi Santos - Coach Cross', imagem: vivi },
    // { nome: 'Yuri - Coach Cross', imagem: yuri },
    { nome: 'Leandro - Musculação|karatê', imagem: leandro },
    // { nome: 'Renan - Musculação', imagem: renam },
    { nome: 'Bruno - Jiu Jitsu (Kids)', imagem: bruno },
    { nome: 'Matheus Black - Jiu Jitsu', imagem: matheus },
    { nome: 'Paulo - Muay Thai', imagem: paulo }
]

export default function Professores() {
    return (
        <>
            <h4>Professores</h4>
            <p>Listagem dos professores</p>
            <hr />
            
            <div className="d-flex justify-content-center flex-column">
                {professores.map((item: any, index: any) => (
                    <div key={index} className="text-center mt-3 mb-3 d-flex flex-column justify-content-center">
                        <img src={item.imagem} width="240px" className="mb-3 rounded mx-auto" alt="..." />
                        <h3>{item.nome}</h3>
                        <hr />
                    </div>
                ))}
            </div>
            
            
        </>
    )
}
