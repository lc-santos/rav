# Move a engrenagem para DEPOIS do botao de perfil em todos os arquivos

$files = @(
    'painel-admin.php',
    'acessos.php',
    'estacionamento.php',
    'relatorios.php',
    'gerenciar_cadastros.php',
    'gerenciar_condutores.php'
)

# Padrao antigo: gear ANTES do dropdown wrapper
$oldPattern = '<div class="d-flex align-items-center gap-2">
                <a href="configuracoes.php" class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center nav-gear-btn" title="Configurações">
                    <i class="bi bi-gear-fill text-secondary"></i>
                </a>
                <div class="dropdown">'

$newPattern = '<div class="d-flex align-items-center gap-2">
                <div class="dropdown">'

$gearAfter_old = '<li><a class="dropdown-item text-danger fw-bold" href="sair.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                    </ul>
                </div>
            </div>'

$gearAfter_new = '<li><a class="dropdown-item text-danger fw-bold" href="sair.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                    </ul>
                </div>
                <a href="configuracoes.php" class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center nav-gear-btn" title="Configurações">
                    <i class="bi bi-gear-fill text-secondary"></i>
                </a>
            </div>'

foreach ($f in $files) {
    $c = [System.IO.File]::ReadAllText($f)
    $c = $c.Replace($oldPattern, $newPattern)
    $c = $c.Replace($gearAfter_old, $gearAfter_new)
    [System.IO.File]::WriteAllText($f, $c)
    Write-Host "Updated: $f"
}
Write-Host "Done."
