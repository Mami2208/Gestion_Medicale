<div id="sidebar">
    <div class="sidebar-header">
        <h3>Espace Patient</h3>
    </div>
    
    <button id="sidebarCollapse" class="d-md-none btn btn-sm btn-light position-absolute" style="top: 20px; right: -40px; border-radius: 0 5px 5px 0; box-shadow: 2px 0 5px rgba(0,0,0,0.1);">
        <i class="fas fa-bars"></i>
    </button>

    <ul class="list-unstyled components">
        <li>
            <a href="{{ route('patient.dashboard') }}" class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Tableau de bord</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('patient.dossier-medical') }}" class="{{ request()->routeIs('patient.dossier-medical*') ? 'active' : '' }}">
                <i class="fas fa-folder-open"></i>
                <span>Mon dossier médical</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('patient.appointments') }}" class="{{ request()->routeIs('patient.appointments*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Mes rendez-vous</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('patient.profile') }}" class="{{ request()->routeIs('patient.profile*') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i>
                <span>Mon profil</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('patient.dicom.viewer') }}" class="{{ request()->routeIs('patient.dicom*') ? 'active' : '' }}">
                <i class="fas fa-x-ray"></i>
                <span>Mes images médicales</span>
            </a>
        </li>

    </ul>
</div>
