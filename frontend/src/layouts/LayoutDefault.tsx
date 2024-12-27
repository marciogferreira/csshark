import Sidebar from "./SideBar"

type DataProps = {
    children: React.ReactNode | null
}
export default function LayoutDefault({ children }: DataProps) {
    return(
        <div className="container-fluid">
            <Sidebar />
            {children}
        </div>
    )
}