workspace "Laravel Invoice Package" "Architecture model for the Invoice package with external integrations" {
    !identifiers hierarchical
    !docs docs
    !adrs adr

    model {
        developer = person "Developer"
        system = softwareSystem "Laravel application" {
            webApp = container "Laravel App"
            package = container "Invoice Package"
            api = container "Invoice API"
            database = container "Database" "Read from and writes to"
            queue = container "Queue Worker" "Handles async jobs (webhooks, retries, invoice generation)" "Laravel Queue"
            events = container "Event dispatcher" "Dispatches events to main-application"
        }

        provider = softwareSystem "Invoice System"

        system.package -> system.api "Makes API call to"
        system.api -> provider "Reads from and writes to"

        system.webApp -> system.package "Uses invoice package"
        system.package -> system.database "Read from and writes to"
        system.package -> system.queue "Dispatches jobs"
        system.package -> system.events "Dispatches events"

        system.package -> system.webApp "Dispatches events"
    }

    views {
        systemContext system "Diagram1" {
            include *
            autolayout lr
        }

        container system {
            include *
            autoLayout lr
        }

        theme default
    }
}
