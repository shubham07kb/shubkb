case $1 in
    run-check)
        phpcs .
        if [ $? -eq 0 ]; then
            echo "All pass"
        fi
        ;;
    fix-all)
        phpcbf .
        if [ $? -eq 0 ]; then
            echo "All pass"
        fi
        ;;
    build-all)
        npm run build
        ;;
    build)
        ;;
    *)
        echo "Invalid command"
        ;;
esac
