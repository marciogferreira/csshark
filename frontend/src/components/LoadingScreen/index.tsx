export default function LoadingScreen() {
    const visible = false;
    return (
        <div 
            className="loading" 
            style={{ 
                display: visible ? 'block' : 'none',
                backgroundColor: 'rgba(0,0,0,0.5)', 
                position: 'fixed', 
                zIndex: 1000, 
                width: '100%',
                height: '100vh',
                justifyContent: "center",
                alignItems: 'center'
            }}>
                <div 
                    className="content"
                    style={{
                        textAlign: 'center',
                        color: '#FFF',
                        marginTop: '20%'
                    }}>
                        Por favor, aguarde...
                </div>
        </div>
    );
}