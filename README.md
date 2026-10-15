# versi

## Final responsibility rule

SemanticVersionService -> arithmetic only

CreateVersion -> create-version business operation

ReleaseVersion -> release transition

Version -> persisted state and content behavior

VersionBuilder -> querying only

HasVersions -> owner-facing relationship/query conveniences

VersionsRelationManager -> contextual user interface

VersionResource -> administrative inspection/editing


```text
versi/
├── config/
│   └── bites.php
├── database/
│   └── migrations/
│       ├── 0002_01_01_000102_create_versions_table.php
│       └── xxxx_xx_xx_xxxxxx_add_version_indexes.php
└── src/
    ├── Actions/
    │   ├── CreateVersion.php
    │   └── ReleaseVersion.php
    ├── Builders/
    │   └── VersionBuilder.php
    ├── Enums/
    │   ├── ContentType.php
    │   ├── VersionIncrementType.php
    │   └── VersionStatus.php
    ├── Http/UI/Admin/Resources/Versions/
    │   ├── Pages/
    │   │   ├── EditVersion.php
    │   │   ├── ListVersions.php
    │   │   └── ViewVersion.php
    │   ├── RelationManagers/
    │   │   └── VersionsRelationManager.php
    │   ├── Schemas/
    │   │   ├── VersionForm.php
    │   │   └── VersionInfolist.php
    │   ├── Tables/
    │   │   └── VersionsTable.php
    │   └── VersionResource.php
    ├── Models/
    │   └── Version.php
    ├── Services/
    │   └── SemanticVersionService.php
    ├── Traits/
    │   ├── HasVersions.php
    │   └── ResourceHasVersionRelations.php
    └── VersioningServiceProvider.php
```