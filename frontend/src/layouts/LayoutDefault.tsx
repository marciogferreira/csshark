import { useContext } from "react"
import Sidebar from "./SideBar"
import AuthContext from "../contexts/AuthContext"
import { Navigate } from "react-router-dom"

type DataProps = {
    children: React.ReactNode | null
}
export default function LayoutDefault({ children }: DataProps) {
    const { isLogged } = useContext(AuthContext)

    if(!isLogged) {
        return <Navigate to="/login" />
    }

    return(
        <>
            <div className="container-fluid">
                <Sidebar />
            </div>
            <div className="container">
                {children}
            </div>
        </>
    )
}