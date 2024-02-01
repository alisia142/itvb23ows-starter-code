FROM python:3.8
WORKDIR /app
RUN pip install Flask==3.0.0
COPY ./itvb23ows-hive-ai .
CMD flask --app app run --debug